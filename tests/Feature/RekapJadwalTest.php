<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Ruangan;
use App\Models\Tentor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RekapJadwalTest extends TestCase
{
    use DatabaseTransactions;

    protected function getSuperadmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.rekap.test@elipsacademy.com'],
            [
                'nama' => 'Superadmin Rekap Tester',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
            ]
        );
    }

    protected function getAdminWithCabang(Cabang $cabang): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.rekap.' . $cabang->id . '@elipsacademy.com'],
            [
                'nama' => 'Admin Rekap ' . $cabang->nama_cabang,
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'cabang_id' => $cabang->id,
            ]
        );
    }

    public function test_guest_is_redirected_from_rekap_routes(): void
    {
        $this->get(route('admin.rekap.index'))->assertRedirect('/login');
        $this->get(route('admin.rekap.export-excel'))->assertRedirect('/login');
        $this->get(route('superadmin.rekap.index'))->assertRedirect('/login');
        $this->get(route('superadmin.rekap.export-excel'))->assertRedirect('/login');
        $this->get(route('superadmin.rekap.export-pdf'))->assertRedirect('/login');
    }

    public function test_admin_cannot_access_superadmin_rekap_routes(): void
    {
        $cabang = Cabang::firstOrCreate(['nama_cabang' => 'Cabang Test Admin'], ['alamat' => 'Alamat', 'status' => 'aktif']);
        $admin = $this->getAdminWithCabang($cabang);

        $this->actingAs($admin)->get(route('superadmin.rekap.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('superadmin.rekap.export-excel'))->assertForbidden();
        $this->actingAs($admin)->get(route('superadmin.rekap.export-pdf'))->assertForbidden();
    }

    public function test_admin_rekap_monthly_isolation_and_display(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 9, 20, 10, 0, 0));

        $cabangA = Cabang::firstOrCreate(['nama_cabang' => 'Cabang Rekap A'], ['alamat' => 'Alamat A', 'status' => 'aktif']);
        $cabangB = Cabang::firstOrCreate(['nama_cabang' => 'Cabang Rekap B'], ['alamat' => 'Alamat B', 'status' => 'aktif']);

        $program = Program::firstOrCreate(['nama_program' => 'Program Rekap'], ['kategori' => 'Reguler', 'status' => 'aktif']);
        $tentor = Tentor::firstOrCreate(['nama' => 'Tentor Rekap'], ['no_hp' => '0811111111', 'status' => 'aktif']);

        // Schedule for Cabang A in September 2026 (before 20 Sept)
        $jadwalA = Jadwal::create([
            'nama_kelas' => 'Kelas Rahasia A',
            'cabang_id' => $cabangA->id,
            'program_id' => $program->id,
            'tentor_id' => $tentor->id,
            'tanggal' => '2026-09-15',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'status' => 'selesai',
        ]);

        // Schedule for Cabang B in September 2026
        $jadwalB = Jadwal::create([
            'nama_kelas' => 'Kelas Rahasia B',
            'cabang_id' => $cabangB->id,
            'program_id' => $program->id,
            'tentor_id' => $tentor->id,
            'tanggal' => '2026-09-15',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'status' => 'terjadwal',
        ]);

        $adminA = $this->getAdminWithCabang($cabangA);

        $response = $this->actingAs($adminA)->get(route('admin.rekap.index', ['bulan' => 9, 'tahun' => 2026]));
        $response->assertOk();
        $response->assertSee('Cabang Rekap A');
        $response->assertSee('Bulan Berjalan');
        $response->assertSee('Program Rekap');

        // Confirm "Nama Kelas" column is NOT shown on the table
        $response->assertDontSee('Kelas Rahasia A');
        $response->assertDontSee('Kelas Rahasia B');

        // Cabang B schedule must not be displayed for Admin A
        $content = $response->getContent();
        $this->assertStringNotContainsString('Cabang Rekap B', $content);

        // Test Excel Export for Admin
        $excelResponse = $this->actingAs($adminA)->get(route('admin.rekap.export-excel', ['bulan' => 9, 'tahun' => 2026]));
        $excelResponse->assertOk();
        $this->assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $excelResponse->headers->get('Content-Type')
        );

        Carbon::setTestNow();
    }

    public function test_superadmin_rekap_custom_date_range_and_exports(): void
    {
        $cabang = Cabang::firstOrCreate(['nama_cabang' => 'Cabang SA Rekap'], ['alamat' => 'Alamat', 'status' => 'aktif']);
        $program = Program::firstOrCreate(['nama_program' => 'Web Design SA'], ['kategori' => 'Reguler', 'status' => 'aktif']);
        $tentor = Tentor::firstOrCreate(['nama' => 'Tentor SA'], ['no_hp' => '0822222222', 'status' => 'aktif']);

        // Schedule across custom range
        $jadwal1 = Jadwal::create([
            'nama_kelas' => 'Private Web 1',
            'cabang_id' => $cabang->id,
            'program_id' => $program->id,
            'tentor_id' => $tentor->id,
            'jenis_kelas' => 'Private',
            'tanggal' => '2026-08-15',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'status' => 'selesai',
        ]);

        $jadwal2 = Jadwal::create([
            'nama_kelas' => 'Rombel Web 2',
            'cabang_id' => $cabang->id,
            'program_id' => $program->id,
            'tentor_id' => $tentor->id,
            'jenis_kelas' => 'Rombel',
            'tanggal' => '2026-10-25',
            'jam_mulai' => '15:00:00',
            'jam_selesai' => '17:00:00',
            'status' => 'terjadwal',
        ]);

        $superadmin = $this->getSuperadmin();

        // 1. Cross month/year range filter
        $response = $this->actingAs($superadmin)->get(route('superadmin.rekap.index', [
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-10-31',
            'cabang_id' => 'semua',
            'status' => 'semua',
            'jenis_kelas' => 'semua',
        ]));
        $response->assertOk();
        $response->assertSee('Web Design SA');
        $response->assertSee('Cabang SA Rekap');

        // Confirm Nama Kelas is NOT present on the table
        $response->assertDontSee('Private Web 1');
        $response->assertDontSee('Rombel Web 2');

        // 2. Filter by status: 'selesai'
        $responseStatus = $this->actingAs($superadmin)->get(route('superadmin.rekap.index', [
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-10-31',
            'status' => 'selesai',
        ]));
        $responseStatus->assertOk();
        $responseStatus->assertSee('13:00');

        // 3. Test Superadmin Excel Export
        $excelResponse = $this->actingAs($superadmin)->get(route('superadmin.rekap.export-excel', [
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-10-31',
        ]));
        $excelResponse->assertOk();
        $this->assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $excelResponse->headers->get('Content-Type')
        );

        // 4. Test Superadmin PDF Export
        $pdfResponse = $this->actingAs($superadmin)->get(route('superadmin.rekap.export-pdf', [
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-10-31',
        ]));
        $pdfResponse->assertOk();
        $this->assertEquals('application/pdf', $pdfResponse->headers->get('Content-Type'));
    }

    public function test_navigation_buttons_present(): void
    {
        $superadmin = $this->getSuperadmin();
        $cabang = Cabang::firstOrCreate(['nama_cabang' => 'Cabang Nav Test'], ['alamat' => 'Alamat', 'status' => 'aktif']);
        $admin = $this->getAdminWithCabang($cabang);

        // Admin dashboard has btnRekapBulanan
        $responseAdmin = $this->actingAs($admin)->get(route('admin.dashboard'));
        $responseAdmin->assertOk();
        $responseAdmin->assertSee('id="btnRekapBulanan"', false);

        // All superadmin pages have nav-superadmin-rekap
        $routes = [
            'superadmin.dashboard',
            'superadmin.cabang.index',
            'superadmin.jadwal.index',
            'superadmin.program.index',
            'superadmin.tentor.index',
            'superadmin.ruangan.index',
            'superadmin.user.index',
            'superadmin.setting.index',
            'superadmin.rekap.index',
        ];

        foreach ($routes as $route) {
            $resp = $this->actingAs($superadmin)->get(route($route));
            $resp->assertOk();
            $resp->assertSee('id="nav-superadmin-rekap"', false);
            $resp->assertSee('Rekap Jadwal');
        }

        // Active class on superadmin.rekap.index
        $rekapView = $this->actingAs($superadmin)->get(route('superadmin.rekap.index'));
        $this->assertMatchesRegularExpression(
            '/id="nav-superadmin-rekap"[^>]*class="[^"]*bg-primary-container[^"]*"/',
            $rekapView->getContent()
        );
    }
}

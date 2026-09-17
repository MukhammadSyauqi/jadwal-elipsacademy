<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Tentor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic master data needed for testing if not present
        Cabang::firstOrCreate(['id' => 1], ['nama_cabang' => 'Buduran', 'status' => 'aktif']);
        Cabang::firstOrCreate(['id' => 2], ['nama_cabang' => 'Candi', 'status' => 'aktif']);

        Program::firstOrCreate(['id' => 1], ['nama_program' => 'Microsoft Office', 'kategori' => 'Office', 'status' => 'aktif']);
        Program::firstOrCreate(['id' => 2], ['nama_program' => 'Web Programming', 'kategori' => 'Programming', 'status' => 'aktif']);

        Tentor::firstOrCreate(['id' => 1], ['nama' => 'Budi Santoso', 'no_hp' => '081234567890', 'keahlian' => 'Office', 'status' => 'aktif']);
        Tentor::firstOrCreate(['id' => 2], ['nama' => 'Andi Pratama', 'no_hp' => '081234567891', 'keahlian' => 'Programming', 'status' => 'aktif']);
    }

    protected function getAdminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.dashboard.test@elipsacademy.com'],
            [
                'nama' => 'Admin Test',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }

    protected function getSuperadminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.dashboard.test@elipsacademy.com'],
            [
                'nama' => 'Super Admin Test',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard_and_see_components(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Elips Academy');
        $response->assertSee('Jadwal Kelas');
        $response->assertSee('Selamat Datang, Admin Test');
        $response->assertSee('Total Jadwal');
        $response->assertSee('Sedang Berlangsung');
        $response->assertSee('Selesai');
        $response->assertSee('Dibatalkan');
        $response->assertSee('Semua Kelas');
        $response->assertSee('Pagi (08:00 - 12:00)');
        $response->assertSee('Siang (13:00 - 15:00)');
        $response->assertSee('Sore (15:30 - 17:30)');
        $response->assertSee('Malam (18:30 - 20:30)');
    }

    public function test_superadmin_can_access_admin_dashboard(): void
    {
        $superadmin = $this->getSuperadminUser();

        $response = $this->actingAs($superadmin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Elips Academy');
    }

    public function test_summary_cards_calculate_accurately(): void
    {
        $admin = $this->getAdminUser();
        $testDate = '2026-10-15';

        // Clear existing test schedules for this specific date and branch
        Jadwal::where('cabang_id', 1)->where('tanggal', $testDate)->delete();

        // 1 completed
        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'TEST-001',
            'jenis_kelas' => 'private',
            'tanggal' => $testDate,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'selesai',
        ]);

        // 1 cancelled
        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 2,
            'tentor_id' => 2,
            'nama_kelas' => 'TEST-002',
            'jenis_kelas' => 'rombel',
            'tanggal' => $testDate,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'ruangan' => 'Ruang 2',
            'pertemuan' => 2,
            'status' => 'dibatalkan',
        ]);

        // 1 upcoming / terjadwal
        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'TEST-003',
            'jenis_kelas' => 'business',
            'tanggal' => $testDate,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'ruangan' => 'Ruang 3',
            'pertemuan' => 3,
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
        ]));

        $response->assertStatus(200);
        $summary = $response->viewData('summary');

        $this->assertEquals(3, $summary['total']);
        $this->assertEquals(1, $summary['completed']);
        $this->assertEquals(1, $summary['cancelled']);
    }

    public function test_session_filter_works_properly(): void
    {
        $admin = $this->getAdminUser();
        $testDate = '2026-10-16';

        Jadwal::where('cabang_id', 1)->where('tanggal', $testDate)->delete();

        // Morning (09:00 - 11:00)
        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'PAGI-CLASS',
            'jenis_kelas' => 'private',
            'tanggal' => $testDate,
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'status' => 'terjadwal',
        ]);

        // Afternoon / Sore (16:00 - 18:00)
        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 2,
            'tentor_id' => 2,
            'nama_kelas' => 'SORE-CLASS',
            'jenis_kelas' => 'rombel',
            'tanggal' => $testDate,
            'jam_mulai' => '16:00:00',
            'jam_selesai' => '18:00:00',
            'status' => 'terjadwal',
        ]);

        // Filter pagi
        $responsePagi = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
            'sesi' => 'pagi',
        ]));
        $responsePagi->assertSee('PAGI-CLASS');
        $responsePagi->assertDontSee('SORE-CLASS');

        // Filter sore
        $responseSore = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
            'sesi' => 'sore',
        ]));
        $responseSore->assertSee('SORE-CLASS');
        $responseSore->assertDontSee('PAGI-CLASS');
    }

    public function test_search_by_class_name_or_tentor(): void
    {
        $admin = $this->getAdminUser();
        $testDate = '2026-10-17';

        Jadwal::where('cabang_id', 1)->where('tanggal', $testDate)->delete();

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1, // Budi Santoso
            'nama_kelas' => 'SEARCH-BUDI-01',
            'jenis_kelas' => 'private',
            'tanggal' => $testDate,
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'status' => 'terjadwal',
        ]);

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 2,
            'tentor_id' => 2, // Andi Pratama
            'nama_kelas' => 'SEARCH-ANDI-02',
            'jenis_kelas' => 'rombel',
            'tanggal' => $testDate,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'status' => 'terjadwal',
        ]);

        // Search by class name
        $responseSearch = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
            'q' => 'SEARCH-BUDI',
        ]));
        $responseSearch->assertSee('SEARCH-BUDI-01');
        $responseSearch->assertDontSee('SEARCH-ANDI-02');

        // Search by tentor name
        $responseTentor = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
            'q' => 'Andi',
        ]));
        $responseTentor->assertSee('SEARCH-ANDI-02');
        $responseTentor->assertDontSee('SEARCH-BUDI-01');
    }

    public function test_date_navigation_hari_ini_and_besok(): void
    {
        $admin = $this->getAdminUser();
        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        Jadwal::where('cabang_id', 1)->whereIn('tanggal', [$today, $tomorrow])->delete();

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'TODAY-UNIQUE-CLASS',
            'jenis_kelas' => 'private',
            'tanggal' => $today,
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'status' => 'terjadwal',
        ]);

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 2,
            'tentor_id' => 2,
            'nama_kelas' => 'TOMORROW-UNIQUE-CLASS',
            'jenis_kelas' => 'rombel',
            'tanggal' => $tomorrow,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status' => 'terjadwal',
        ]);

        // Request today
        $responseToday = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $today,
        ]));
        $responseToday->assertSee('TODAY-UNIQUE-CLASS');
        $responseToday->assertDontSee('TOMORROW-UNIQUE-CLASS');

        // Request tomorrow
        $responseTomorrow = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $tomorrow,
        ]));
        $responseTomorrow->assertSee('TOMORROW-UNIQUE-CLASS');
        $responseTomorrow->assertDontSee('TODAY-UNIQUE-CLASS');
    }
}

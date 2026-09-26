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
        $response->assertSee('Akan Datang');
        $response->assertSee('Semua Cabang');
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
            'mode_kelas' => 'offline',
            'catatan' => 'Pagi Special Session',
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
            'mode_kelas' => 'online',
            'catatan' => 'Sore Special Session',
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
        $responsePagi->assertSee('Pagi Special Session');
        $responsePagi->assertDontSee('Sore Special Session');

        // Filter sore
        $responseSore = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
            'sesi' => 'sore',
        ]));
        $responseSore->assertSee('Sore Special Session');
        $responseSore->assertDontSee('Pagi Special Session');
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
            'mode_kelas' => 'offline',
            'catatan' => 'Materi Budi Office',
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
            'mode_kelas' => 'online',
            'catatan' => 'Materi Andi Programming',
            'tanggal' => $testDate,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'status' => 'terjadwal',
        ]);

        // Search by class name (still matches query, assertions check visible notes)
        $responseSearch = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
            'q' => 'SEARCH-BUDI',
        ]));
        $responseSearch->assertSee('Materi Budi Office');
        $responseSearch->assertDontSee('Materi Andi Programming');

        // Search by tentor name
        $responseTentor = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
            'q' => 'Andi',
        ]));
        $responseTentor->assertSee('Materi Andi Programming');
        $responseTentor->assertDontSee('Materi Budi Office');
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
            'mode_kelas' => 'offline',
            'catatan' => 'Today Catatan Khusus',
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
            'mode_kelas' => 'online',
            'catatan' => 'Tomorrow Catatan Khusus',
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
        $responseToday->assertSee('Today Catatan Khusus');
        $responseToday->assertDontSee('Tomorrow Catatan Khusus');

        // Request tomorrow
        $responseTomorrow = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $tomorrow,
        ]));
        $responseTomorrow->assertSee('Tomorrow Catatan Khusus');
        $responseTomorrow->assertDontSee('Today Catatan Khusus');
    }

    public function test_admin_dashboard_shows_all_branches_by_default(): void
    {
        $admin = $this->getAdminUser();
        $testDate = '2026-10-25';

        Jadwal::whereIn('cabang_id', [1, 2])->where('tanggal', $testDate)->delete();

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'BUDURAN-CLASS',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'catatan' => 'Buduran Unique Schedule',
            'tanggal' => $testDate,
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'status' => 'terjadwal',
        ]);

        Jadwal::create([
            'cabang_id' => 2,
            'program_id' => 2,
            'tentor_id' => 2,
            'nama_kelas' => 'CANDI-CLASS',
            'jenis_kelas' => 'rombel',
            'mode_kelas' => 'online',
            'catatan' => 'Candi Unique Schedule',
            'tanggal' => $testDate,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'status' => 'terjadwal',
        ]);

        // When opened without cabang_id, it should show schedules from both branches
        $response = $this->actingAs($admin)->get(route('admin.dashboard', [
            'tanggal' => $testDate,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Buduran Unique Schedule');
        $response->assertSee('Candi Unique Schedule');
        $response->assertSee('Cabang Buduran');
        $response->assertSee('Cabang Candi');
        $response->assertSee('Offline');
        $response->assertSee('Online');

        // When filtered by cabang_id = 1
        $responseFiltered = $this->actingAs($admin)->get(route('admin.dashboard', [
            'cabang_id' => 1,
            'tanggal' => $testDate,
        ]));

        $responseFiltered->assertSee('Buduran Unique Schedule');
        $responseFiltered->assertDontSee('Candi Unique Schedule');
    }

    public function test_empty_state_does_not_show_kembali_ke_hari_ini_when_viewing_today(): void
    {
        $admin = $this->getAdminUser();
        $todayDate = Carbon::today()->toDateString();

        // Clear schedules for today
        Jadwal::whereDate('tanggal', $todayDate)->delete();

        $response = $this->actingAs($admin)->get(route('admin.dashboard', [
            'tanggal' => $todayDate,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tidak Ada Jadwal Kelas');
        $response->assertDontSee('Kembali ke Hari Ini');
        $response->assertSee('Tambah Jadwal Baru');
    }

    public function test_empty_state_shows_kembali_ke_hari_ini_when_viewing_other_dates(): void
    {
        $admin = $this->getAdminUser();
        $futureDate = Carbon::today()->addDays(5)->toDateString();

        // Clear schedules for future date
        Jadwal::whereDate('tanggal', $futureDate)->delete();

        $response = $this->actingAs($admin)->get(route('admin.dashboard', [
            'tanggal' => $futureDate,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tidak Ada Jadwal Kelas');
        $response->assertSee('Kembali ke Hari Ini');
        $response->assertSee('Tambah Jadwal Baru');
    }

    public function test_gubeng_staff_user_can_access_dashboard_and_create_page(): void
    {
        $gubengCabang = Cabang::firstOrCreate(
            ['nama_cabang' => 'Gubeng'],
            ['alamat' => 'Jl. Raya Gubeng No. 45, Surabaya', 'status' => 'aktif']
        );

        $staffGubeng = User::firstOrCreate(
            ['email' => 'gubeng@elipsacademy.com'],
            ['nama' => 'Staff Gubeng', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        // 1. Dashboard
        $dashResponse = $this->actingAs($staffGubeng)->get(route('admin.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Gubeng');

        // 2. Create Jadwal Page (Cabang Gubeng should be pre-selected and 3 rooms available)
        $createResponse = $this->actingAs($staffGubeng)->get(route('admin.jadwal.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Cabang Gubeng');
        $createResponse->assertSee('Ruang 1');
        $createResponse->assertSee('Ruang 2');
        $createResponse->assertSee('Lab Komputer A');
    }
}



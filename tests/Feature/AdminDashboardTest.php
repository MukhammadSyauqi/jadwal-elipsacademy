<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Ruangan;
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

        $adminGubeng = User::updateOrCreate(
            ['email' => 'admin.gubeng@elipsacademy.com'],
            ['nama' => 'Admin Gubeng', 'password' => Hash::make('password'), 'role' => 'admin', 'cabang_id' => $gubengCabang->id]
        );

        // 1. Dashboard
        $dashResponse = $this->actingAs($adminGubeng)->get(route('admin.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Admin Gubeng');
        $dashResponse->assertSee('Gubeng');
        $dashResponse->assertDontSee('Semua Cabang');

        // 2. Create Jadwal Page (Cabang Gubeng should be pre-selected and 3 rooms available)
        $createResponse = $this->actingAs($adminGubeng)->get(route('admin.jadwal.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Cabang Gubeng');
        $createResponse->assertSee('Ruang 1');
        $createResponse->assertSee('Ruang 2');
        $createResponse->assertSee('Ruang 3');
    }

    public function test_buduran_admin_user_can_access_dashboard_and_create_page(): void
    {
        $buduranCabang = Cabang::firstOrCreate(
            ['nama_cabang' => 'Buduran'],
            ['alamat' => 'Jl. Raya Buduran, Sidoarjo', 'status' => 'aktif']
        );

        $adminBuduran = User::updateOrCreate(
            ['email' => 'admin.buduran@elipsacademy.com'],
            ['nama' => 'Admin Buduran', 'password' => Hash::make('password'), 'role' => 'admin', 'cabang_id' => $buduranCabang->id]
        );

        // 1. Dashboard
        $dashResponse = $this->actingAs($adminBuduran)->get(route('admin.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Admin Buduran');
        $dashResponse->assertSee('Buduran');
        $dashResponse->assertDontSee('Semua Cabang');

        // 2. Create Jadwal Page (Cabang Buduran should be pre-selected)
        $createResponse = $this->actingAs($adminBuduran)->get(route('admin.jadwal.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Cabang Buduran');
    }

    public function test_admin_only_sees_schedules_from_own_cabang(): void
    {
        $buduran = Cabang::firstOrCreate(['id' => 1], ['nama_cabang' => 'Buduran', 'status' => 'aktif']);
        $candi = Cabang::firstOrCreate(['id' => 2], ['nama_cabang' => 'Candi', 'status' => 'aktif']);

        $adminBuduran = User::updateOrCreate(
            ['email' => 'admin.buduran.isolation@elipsacademy.com'],
            ['nama' => 'Admin Buduran Iso', 'password' => Hash::make('password'), 'role' => 'admin', 'cabang_id' => $buduran->id]
        );

        $today = Carbon::today()->toDateString();

        // Create 1 schedule in Buduran, 1 in Candi
        $jadwalBuduran = Jadwal::create([
            'cabang_id' => $buduran->id,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'KELAS-BUDURAN-ISO',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => $today,
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:30',
            'ruangan' => 'Ruang Isolasi Buduran',
            'catatan' => 'Catatan Khusus Buduran',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        $jadwalCandi = Jadwal::create([
            'cabang_id' => $candi->id,
            'program_id' => 1,
            'tentor_id' => 2,
            'nama_kelas' => 'KELAS-CANDI-ISO',
            'jenis_kelas' => 'rombel',
            'mode_kelas' => 'offline',
            'tanggal' => $today,
            'jam_mulai' => '10:00',
            'jam_selesai' => '11:30',
            'ruangan' => 'Ruang Isolasi Candi',
            'catatan' => 'Catatan Khusus Candi',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($adminBuduran)->get(route('admin.dashboard', ['tanggal' => $today]));
        $response->assertStatus(200);
        $response->assertSee('Ruang Isolasi Buduran');
        $response->assertSee('Catatan Khusus Buduran');
        $response->assertDontSee('Ruang Isolasi Candi');
        $response->assertDontSee('Catatan Khusus Candi');

        // Cleanup
        $jadwalBuduran->delete();
        $jadwalCandi->delete();
    }

    public function test_admin_dashboard_shows_ruangan_dropdown_and_semua_ruangan(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.dashboard', ['cabang_id' => 1]));
        $response->assertStatus(200);
        $response->assertSee('Semua Ruangan');
        $response->assertSee('Ruang 1');
    }

    public function test_admin_dashboard_filters_schedules_by_ruangan_id(): void
    {
        $admin = $this->getAdminUser();
        $today = Carbon::today()->toDateString();

        $ruangA = Ruangan::create([
            'cabang_id' => 1,
            'nama_ruangan' => 'Ruang Alfa Filter Test',
            'kapasitas' => 15,
            'status' => 'aktif',
        ]);
        $ruangB = Ruangan::create([
            'cabang_id' => 1,
            'nama_ruangan' => 'Ruang Beta Filter Test',
            'kapasitas' => 25,
            'status' => 'aktif',
        ]);

        try {
            $jadwalA = Jadwal::create([
                'cabang_id' => 1,
                'ruangan_id' => $ruangA->id,
                'program_id' => 1,
                'tentor_id' => 1,
                'nama_kelas' => 'KELAS-RUANG-ALFA',
                'jenis_kelas' => 'private',
                'mode_kelas' => 'offline',
                'tanggal' => $today,
                'jam_mulai' => '08:00',
                'jam_selesai' => '09:30',
                'ruangan' => $ruangA->nama_ruangan,
                'catatan' => 'Catatan Khusus Sesi Alfa',
                'pertemuan' => 1,
                'status' => 'terjadwal',
            ]);

            $jadwalB = Jadwal::create([
                'cabang_id' => 1,
                'ruangan_id' => $ruangB->id,
                'program_id' => 1,
                'tentor_id' => 2,
                'nama_kelas' => 'KELAS-RUANG-BETA',
                'jenis_kelas' => 'rombel',
                'mode_kelas' => 'offline',
                'tanggal' => $today,
                'jam_mulai' => '10:00',
                'jam_selesai' => '11:30',
                'ruangan' => $ruangB->nama_ruangan,
                'catatan' => 'Catatan Khusus Sesi Beta',
                'pertemuan' => 1,
                'status' => 'terjadwal',
            ]);

            // 1. Filter by Ruang A
            $responseA = $this->actingAs($admin)->get(route('admin.dashboard', [
                'cabang_id' => 1,
                'tanggal' => $today,
                'ruangan_id' => $ruangA->id,
            ]));
            $responseA->assertStatus(200);
            $responseA->assertSee('Catatan Khusus Sesi Alfa');
            $responseA->assertDontSee('Catatan Khusus Sesi Beta');
            $responseA->assertSee(route('admin.jadwal.show', $jadwalA->id));
            $responseA->assertDontSee(route('admin.jadwal.show', $jadwalB->id));
            $responseA->assertSee('Ruang Alfa Filter Test');

            // 2. Filter by Ruang B
            $responseB = $this->actingAs($admin)->get(route('admin.dashboard', [
                'cabang_id' => 1,
                'tanggal' => $today,
                'ruangan_id' => $ruangB->id,
            ]));
            $responseB->assertStatus(200);
            $responseB->assertSee('Catatan Khusus Sesi Beta');
            $responseB->assertDontSee('Catatan Khusus Sesi Alfa');
            $responseB->assertSee(route('admin.jadwal.show', $jadwalB->id));
            $responseB->assertDontSee(route('admin.jadwal.show', $jadwalA->id));

            // 3. Filter "semua" or empty displays both
            $responseAll = $this->actingAs($admin)->get(route('admin.dashboard', [
                'cabang_id' => 1,
                'tanggal' => $today,
                'ruangan_id' => '',
            ]));
            $responseAll->assertStatus(200);
            $responseAll->assertSee('Catatan Khusus Sesi Alfa');
            $responseAll->assertSee('Catatan Khusus Sesi Beta');
            $responseAll->assertSee(route('admin.jadwal.show', $jadwalA->id));
            $responseAll->assertSee(route('admin.jadwal.show', $jadwalB->id));
        } finally {
            if (isset($jadwalA)) $jadwalA->delete();
            if (isset($jadwalB)) $jadwalB->delete();
            $ruangA->delete();
            $ruangB->delete();
        }
    }

    public function test_admin_dashboard_card_displays_ruangan_name_from_relation_and_legacy_fallback(): void
    {
        $admin = $this->getAdminUser();
        $today = Carbon::today()->toDateString();

        $ruangRelasi = Ruangan::create([
            'cabang_id' => 1,
            'nama_ruangan' => 'Ruang Laboratorium Cerdas',
            'kapasitas' => 10,
            'status' => 'aktif',
        ]);

        try {
            // Schedule with relation
            $jadwalWithRel = Jadwal::create([
                'cabang_id' => 1,
                'ruangan_id' => $ruangRelasi->id,
                'program_id' => 1,
                'tentor_id' => 1,
                'nama_kelas' => 'KELAS-RELASI-ROOM',
                'jenis_kelas' => 'private',
                'mode_kelas' => 'offline',
                'tanggal' => $today,
                'jam_mulai' => '13:00',
                'jam_selesai' => '14:30',
                'ruangan' => 'Old Room String',
                'pertemuan' => 1,
                'status' => 'terjadwal',
            ]);

            // Schedule with only legacy string
            $jadwalLegacy = Jadwal::create([
                'cabang_id' => 1,
                'ruangan_id' => null,
                'program_id' => 1,
                'tentor_id' => 1,
                'nama_kelas' => 'KELAS-LEGACY-ROOM',
                'jenis_kelas' => 'private',
                'mode_kelas' => 'offline',
                'tanggal' => $today,
                'jam_mulai' => '15:00',
                'jam_selesai' => '16:30',
                'ruangan' => 'Ruang Warisan Lama',
                'pertemuan' => 1,
                'status' => 'terjadwal',
            ]);

            $response = $this->actingAs($admin)->get(route('admin.dashboard', [
                'cabang_id' => 1,
                'tanggal' => $today,
            ]));

            $response->assertStatus(200);
            $response->assertSee('Ruang Laboratorium Cerdas');
            $response->assertSee('Ruang Warisan Lama');
        } finally {
            if (isset($jadwalWithRel)) $jadwalWithRel->delete();
            if (isset($jadwalLegacy)) $jadwalLegacy->delete();
            $ruangRelasi->delete();
        }
    }

    public function test_superadmin_views_have_ruangan_in_sidebar_navigation_in_correct_order(): void
    {
        $superadmin = $this->getSuperadminUser();

        $routes = [
            'superadmin.dashboard',
            'superadmin.cabang.index',
            'superadmin.jadwal.index',
            'superadmin.program.index',
            'superadmin.tentor.index',
            'superadmin.user.index',
            'superadmin.ruangan.index',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($superadmin)->get(route($routeName));
            $response->assertStatus(200);
            $response->assertSee('id="nav-superadmin-ruangan"', false);
            $response->assertSee('Ruangan');

            // Verify order: Tentor appears before Ruangan in sidebar nav
            $content = $response->getContent();
            $posTentor = strpos($content, 'id="nav-superadmin-tentor"');
            $posRuangan = strpos($content, 'id="nav-superadmin-ruangan"');
            $this->assertNotFalse($posTentor, "Tentor nav must exist in $routeName");
            $this->assertNotFalse($posRuangan, "Ruangan nav must exist in $routeName");
            $this->assertTrue($posTentor < $posRuangan, "Tentor must precede Ruangan in $routeName sidebar");
        }
    }

    public function test_ruangan_nav_is_active_on_superadmin_ruangan_index(): void
    {
        $superadmin = $this->getSuperadminUser();

        $response = $this->actingAs($superadmin)->get(route('superadmin.ruangan.index'));
        $response->assertStatus(200);
        $content = $response->getContent();

        // The ruangan link must contain active class
        $this->assertMatchesRegularExpression(
            '/id="nav-superadmin-ruangan"[^>]*class="[^"]*bg-primary-container[^"]*"/',
            $content
        );
    }
}




<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Tentor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperadminDashboardTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Cabang::firstOrCreate(['id' => 1], ['nama_cabang' => 'Buduran', 'alamat' => 'Jl. Buduran No. 1', 'status' => 'aktif']);
        Cabang::firstOrCreate(['id' => 2], ['nama_cabang' => 'Candi', 'alamat' => 'Jl. Candi No. 2', 'status' => 'aktif']);

        Program::firstOrCreate(['id' => 1], ['nama_program' => 'Microsoft Office', 'kategori' => 'Office', 'status' => 'aktif']);
        Program::firstOrCreate(['id' => 2], ['nama_program' => 'Web Programming', 'kategori' => 'Programming', 'status' => 'aktif']);

        Tentor::firstOrCreate(['id' => 1], ['nama' => 'Budi Santoso', 'no_hp' => '081234567890', 'keahlian' => 'Office', 'status' => 'aktif']);
        Tentor::firstOrCreate(['id' => 2], ['nama' => 'Andi Pratama', 'no_hp' => '081234567891', 'keahlian' => 'Programming', 'status' => 'aktif']);
    }

    protected function getSuperadminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.test@elipsacademy.com'],
            [
                'nama' => 'Superadmin Test',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
    }

    protected function getAdminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.role.test@elipsacademy.com'],
            [
                'nama' => 'Admin Test',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('superadmin.dashboard'))->assertRedirect('/login');
        $this->get(route('superadmin.jadwal.index'))->assertRedirect('/login');
    }

    public function test_admin_is_forbidden_from_superadmin_routes(): void
    {
        $admin = $this->getAdminUser();

        $this->actingAs($admin)->get(route('superadmin.dashboard'))->assertStatus(403);
        $this->actingAs($admin)->get(route('superadmin.jadwal.index'))->assertStatus(403);
    }

    public function test_superadmin_can_access_dashboard_with_metrics_and_schedules(): void
    {
        $superadmin = $this->getSuperadminUser();
        $today = Carbon::today()->toDateString();

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'SUPER-001',
            'jenis_kelas' => 'private',
            'tanggal' => $today,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang, Superadmin Test');
        $response->assertSee('Total Sesi Minggu Ini');
        $response->assertSee('Okupansi Ruang Hari Ini');
        $response->assertSee('Tentor Aktif Hari Ini');
        $response->assertSee('Integritas Jadwal');
        $response->assertSee('SUPER-001');
        $response->assertSee('Ketersediaan Ruang');
        $response->assertSee('Perubahan Jadwal Terakhir');
        $response->assertSee('Pintasan Cepat Akademik');
    }

    public function test_superadmin_can_filter_dashboard_by_sesi_and_search(): void
    {
        $superadmin = $this->getSuperadminUser();
        $today = Carbon::today()->toDateString();

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'PAGI-CLASS',
            'jenis_kelas' => 'private',
            'tanggal' => $today,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        Jadwal::create([
            'cabang_id' => 2,
            'program_id' => 2,
            'tentor_id' => 2,
            'nama_kelas' => 'SORE-CLASS',
            'jenis_kelas' => 'rombel',
            'tanggal' => $today,
            'jam_mulai' => '16:00',
            'jam_selesai' => '18:00',
            'ruangan' => 'Lab IT Candi',
            'pertemuan' => 2,
            'status' => 'terjadwal',
        ]);

        // Filter pagi
        $resPagi = $this->actingAs($superadmin)->get(route('superadmin.dashboard', ['sesi' => 'pagi']));
        $resPagi->assertStatus(200);
        $resPagi->assertSee('PAGI-CLASS');
        $resPagi->assertViewHas('todaySchedules', function ($schedules) {
            return $schedules->contains('nama_kelas', 'PAGI-CLASS') && !$schedules->contains('nama_kelas', 'SORE-CLASS');
        });

        // Search query
        $resSearch = $this->actingAs($superadmin)->get(route('superadmin.dashboard', ['q' => 'SORE-CLASS']));
        $resSearch->assertStatus(200);
        $resSearch->assertSee('SORE-CLASS');
        $resSearch->assertViewHas('todaySchedules', function ($schedules) {
            return $schedules->contains('nama_kelas', 'SORE-CLASS') && !$schedules->contains('nama_kelas', 'PAGI-CLASS');
        });
    }

    public function test_superadmin_can_access_jadwal_index_table(): void
    {
        $superadmin = $this->getSuperadminUser();
        $today = Carbon::today()->toDateString();

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'TABEL-BUDURAN',
            'jenis_kelas' => 'private',
            'tanggal' => $today,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        Jadwal::create([
            'cabang_id' => 2,
            'program_id' => 2,
            'tentor_id' => 2,
            'nama_kelas' => 'TABEL-CANDI',
            'jenis_kelas' => 'rombel',
            'tanggal' => $today,
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:00',
            'ruangan' => 'Lab IT Candi',
            'pertemuan' => 2,
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.jadwal.index', ['q' => 'TABEL']));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Kelas Elips Academy');
        $response->assertSee('TABEL-BUDURAN');
        $response->assertSee('TABEL-CANDI');
        $response->assertSee('Cabang Aktif');
        $response->assertSee('Validasi Jadwal: Sinkron', false);
    }

    public function test_superadmin_can_filter_jadwal_by_cabang_jenis_status(): void
    {
        $superadmin = $this->getSuperadminUser();
        $today = Carbon::today()->toDateString();

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'FILTER-BUDURAN-PRIVATE',
            'jenis_kelas' => 'private',
            'tanggal' => $today,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        Jadwal::create([
            'cabang_id' => 2,
            'program_id' => 2,
            'tentor_id' => 2,
            'nama_kelas' => 'FILTER-CANDI-ROMBEL',
            'jenis_kelas' => 'rombel',
            'tanggal' => $today,
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:00',
            'ruangan' => 'Lab IT Candi',
            'pertemuan' => 2,
            'status' => 'selesai',
        ]);

        // Filter Cabang 1
        $resCabang = $this->actingAs($superadmin)->get(route('superadmin.jadwal.index', ['cabang_id' => 1, 'q' => 'FILTER']));
        $resCabang->assertStatus(200);
        $resCabang->assertSee('FILTER-BUDURAN-PRIVATE');
        $resCabang->assertDontSee('FILTER-CANDI-ROMBEL');

        // Filter Jenis rombel
        $resJenis = $this->actingAs($superadmin)->get(route('superadmin.jadwal.index', ['jenis_kelas' => 'rombel', 'q' => 'FILTER']));
        $resJenis->assertStatus(200);
        $resJenis->assertSee('FILTER-CANDI-ROMBEL');
        $resJenis->assertDontSee('FILTER-BUDURAN-PRIVATE');

        // Filter Status selesai
        $resStatus = $this->actingAs($superadmin)->get(route('superadmin.jadwal.index', ['status' => 'selesai', 'q' => 'FILTER']));
        $resStatus->assertStatus(200);
        $resStatus->assertSee('FILTER-CANDI-ROMBEL');
        $resStatus->assertDontSee('FILTER-BUDURAN-PRIVATE');
    }

    public function test_superadmin_can_filter_jadwal_by_quick_range(): void
    {
        $superadmin = $this->getSuperadminUser();
        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'TODAY-RANGE',
            'jenis_kelas' => 'private',
            'tanggal' => $today,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'TOMORROW-RANGE',
            'jenis_kelas' => 'private',
            'tanggal' => $tomorrow,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 2,
            'status' => 'terjadwal',
        ]);

        // Range hari_ini
        $resHariIni = $this->actingAs($superadmin)->get(route('superadmin.jadwal.index', ['range' => 'hari_ini']));
        $resHariIni->assertStatus(200);
        $resHariIni->assertSee('TODAY-RANGE');
        $resHariIni->assertDontSee('TOMORROW-RANGE');

        // Range besok
        $resBesok = $this->actingAs($superadmin)->get(route('superadmin.jadwal.index', ['range' => 'besok']));
        $resBesok->assertStatus(200);
        $resBesok->assertSee('TOMORROW-RANGE');
        $resBesok->assertDontSee('TODAY-RANGE');
    }

    public function test_superadmin_can_cancel_jadwal_from_management(): void
    {
        $superadmin = $this->getSuperadminUser();
        $today = Carbon::today()->toDateString();

        $jadwal = Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'CANCEL-TARGET',
            'jenis_kelas' => 'private',
            'tanggal' => $today,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($superadmin)->post(route('admin.jadwal.batal', $jadwal->id), [
            'redirect_to' => route('superadmin.jadwal.index'),
        ]);

        $response->assertRedirect(route('superadmin.jadwal.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('dibatalkan', $jadwal->fresh()->status);
    }
}

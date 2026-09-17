<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Tentor;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JadwalCrudTest extends TestCase
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

    protected function getAdminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.crud.test@elipsacademy.com'],
            [
                'nama' => 'Admin CRUD Test',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }

    public function test_guest_cannot_access_crud_routes(): void
    {
        $this->get(route('admin.jadwal.create'))->assertRedirect('/login');
        $this->post(route('admin.jadwal.store'), [])->assertRedirect('/login');
    }

    public function test_admin_can_view_create_jadwal_page(): void
    {
        $admin = $this->getAdminUser();
        $tentor = Tentor::first();

        $response = $this->actingAs($admin)->get(route('admin.jadwal.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Jadwal Kelas');
        $response->assertSee('Cabang Buduran');
        $response->assertSee('Microsoft Office');
        if ($tentor) {
            $response->assertSee($tentor->nama);
        }
    }

    public function test_validation_required_fields(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), []);

        $response->assertSessionHasErrors([
            'cabang_id',
            'program_id',
            'tentor_id',
            'nama_kelas',
            'jenis_kelas',
            'tanggal',
            'jam_mulai',
            'jam_selesai',
            'ruangan',
            'pertemuan',
        ]);
    }

    public function test_validation_jam_selesai_must_be_after_jam_mulai(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'MO-INVALID',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-10-10',
            'jam_mulai' => '11:00',
            'jam_selesai' => '09:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
        ]);

        $response->assertSessionHasErrors(['jam_selesai']);
    }

    public function test_admin_can_create_jadwal_successfully(): void
    {
        $admin = $this->getAdminUser();

        $payload = [
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'MO-TEST-SUCCESS',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-11-20',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'catatan' => 'Pertemuan perdana pengenalan kurikulum',
        ];

        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('jadwal', [
            'nama_kelas' => 'MO-TEST-SUCCESS',
            'status' => 'terjadwal',
            'tanggal' => '2026-11-20',
            'ruangan' => 'Ruang 1',
        ]);
    }

    public function test_detect_tentor_conflict(): void
    {
        $admin = $this->getAdminUser();

        // Existing schedule for Tentor 1: 09:00 - 11:00 in Room 1
        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'EXISTING-TENTOR-1',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-12-01',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Attempt to create overlapping schedule for same Tentor 1: 10:00 - 12:00 (even in different room)
        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => 1,
            'program_id' => 2,
            'tentor_id' => 1,
            'nama_kelas' => 'CONFLICT-TENTOR',
            'jenis_kelas' => 'rombel',
            'tanggal' => '2026-12-01',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'ruangan' => 'Ruang 2',
            'pertemuan' => 1,
        ]);

        $response->assertSessionHasErrors(['tentor_id']);
    }

    public function test_detect_ruangan_conflict(): void
    {
        $admin = $this->getAdminUser();

        // Existing schedule in Ruang 1: 13:00 - 15:00 with Tentor 1
        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'EXISTING-ROOM-1',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-12-02',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'ruangan' => 'Lab Komputer A',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Attempt to book same room with different Tentor 2 at overlapping time 14:00 - 16:00
        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => 1,
            'program_id' => 2,
            'tentor_id' => 2,
            'nama_kelas' => 'CONFLICT-ROOM',
            'jenis_kelas' => 'rombel',
            'tanggal' => '2026-12-02',
            'jam_mulai' => '14:00',
            'jam_selesai' => '16:00',
            'ruangan' => 'Lab Komputer A',
            'pertemuan' => 1,
        ]);

        $response->assertSessionHasErrors(['ruangan']);
    }

    public function test_cancelled_jadwal_does_not_cause_conflict(): void
    {
        $admin = $this->getAdminUser();

        // Cancelled schedule with Tentor 1 and Ruang 1 at 08:00 - 10:00
        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'CANCELLED-SCHEDULE',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-12-03',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'dibatalkan',
        ]);

        // Same time, tentor, and room should succeed because previous one was dibatalkan
        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'NEW-NON-CONFLICT',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-12-03',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('jadwal', ['nama_kelas' => 'NEW-NON-CONFLICT']);
    }

    public function test_admin_can_view_detail_jadwal(): void
    {
        $admin = $this->getAdminUser();

        $jadwal = Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'DETAIL-TEST-01',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-12-05',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 3,
            'status' => 'terjadwal',
            'catatan' => 'Catatan detail pertemuan 3',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.jadwal.show', $jadwal->id));

        $response->assertStatus(200);
        $response->assertSee('DETAIL-TEST-01');
        $response->assertSee($jadwal->tentor->nama);
        $response->assertSee('Catatan detail pertemuan 3');
        $response->assertSee('Ruang 1');
    }

    public function test_admin_can_update_jadwal_without_self_conflict(): void
    {
        $admin = $this->getAdminUser();

        $jadwal = Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'SELF-CONFLICT-TEST',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-12-06',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Updating with same tentor, room, and time should NOT trigger conflict with itself
        $response = $this->actingAs($admin)->put(route('admin.jadwal.update', $jadwal->id), [
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'SELF-CONFLICT-TEST-UPDATED',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-12-06',
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 2,
            'status' => 'terjadwal',
            'catatan' => 'Updated notes',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.jadwal.show', $jadwal->id));

        $this->assertDatabaseHas('jadwal', [
            'id' => $jadwal->id,
            'nama_kelas' => 'SELF-CONFLICT-TEST-UPDATED',
            'pertemuan' => 2,
            'catatan' => 'Updated notes',
        ]);
    }

    public function test_admin_can_cancel_jadwal(): void
    {
        $admin = $this->getAdminUser();

        $jadwal = Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'TO-BE-CANCELLED',
            'jenis_kelas' => 'private',
            'tanggal' => '2026-12-07',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.jadwal.batal', $jadwal->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('jadwal', [
            'id' => $jadwal->id,
            'status' => 'dibatalkan',
        ]);
    }
}

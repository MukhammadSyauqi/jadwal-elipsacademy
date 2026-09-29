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

class JadwalRuanganConflictTest extends TestCase
{
    use DatabaseTransactions;

    protected Cabang $cabangBuduran;
    protected Cabang $cabangCandi;
    protected Program $programOffice;
    protected Program $programCoding;
    protected Tentor $tentor1;
    protected Tentor $tentor2;
    protected Ruangan $ruang1;
    protected Ruangan $ruang2;
    protected Ruangan $ruangCandi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cabangBuduran = Cabang::firstOrCreate(
            ['nama_cabang' => 'Buduran'],
            ['alamat' => 'Jl. Buduran No. 1', 'status' => 'aktif']
        );

        $this->cabangCandi = Cabang::firstOrCreate(
            ['nama_cabang' => 'Candi'],
            ['alamat' => 'Jl. Candi No. 2', 'status' => 'aktif']
        );

        $this->programOffice = Program::firstOrCreate(
            ['nama_program' => 'Microsoft Office'],
            ['kategori' => 'Office', 'status' => 'aktif']
        );

        $this->programCoding = Program::firstOrCreate(
            ['nama_program' => 'Web Programming'],
            ['kategori' => 'Programming', 'status' => 'aktif']
        );

        $this->tentor1 = Tentor::firstOrCreate(
            ['nama' => 'Budi Santoso'],
            ['no_hp' => '081234567890', 'keahlian' => 'Office', 'status' => 'aktif']
        );

        $this->tentor2 = Tentor::firstOrCreate(
            ['nama' => 'Andi Pratama'],
            ['no_hp' => '081234567891', 'keahlian' => 'Programming', 'status' => 'aktif']
        );

        $this->ruang1 = Ruangan::firstOrCreate(
            ['cabang_id' => $this->cabangBuduran->id, 'nama_ruangan' => 'Ruang 1'],
            ['kapasitas' => 15, 'status' => 'aktif']
        );

        $this->ruang2 = Ruangan::firstOrCreate(
            ['cabang_id' => $this->cabangBuduran->id, 'nama_ruangan' => 'Ruang 2'],
            ['kapasitas' => 15, 'status' => 'aktif']
        );

        $this->ruangCandi = Ruangan::firstOrCreate(
            ['cabang_id' => $this->cabangCandi->id, 'nama_ruangan' => 'Ruang A Candi'],
            ['kapasitas' => 20, 'status' => 'aktif']
        );
    }

    protected function getAdminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.conflict.test@elipsacademy.com'],
            [
                'nama' => 'Admin Conflict Tester',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'cabang_id' => $this->cabangBuduran->id,
            ]
        );
    }

    protected function getSuperadminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.conflict.test@elipsacademy.com'],
            [
                'nama' => 'Superadmin Conflict Tester',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
    }

    // 1. Test Dropdown Ruangan dari Database
    public function test_create_and_edit_page_loads_ruangan_from_database(): void
    {
        $admin = $this->getAdminUser();

        $responseCreate = $this->actingAs($admin)->get(route('admin.jadwal.create'));
        $responseCreate->assertOk();
        $responseCreate->assertSee($this->ruang1->nama_ruangan);
        $responseCreate->assertSee($this->ruang2->nama_ruangan);

        $jadwal = Jadwal::create([
            'nama_kelas' => 'MO-TEST-EDIT-ROOM',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-11-25',
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'program_id' => $this->programOffice->id,
            'tentor_id' => $this->tentor1->id,
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'ruangan' => $this->ruang1->nama_ruangan,
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        $responseEdit = $this->actingAs($admin)->get(route('admin.jadwal.edit', $jadwal));
        $responseEdit->assertOk();
        $responseEdit->assertSee($this->ruang1->nama_ruangan);
    }

    // 2. Test API Endpoint: GET /api/ruangan
    public function test_api_get_ruangan_by_cabang(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->getJson(route('api.ruangan.by-cabang', [
            'cabang_id' => $this->cabangBuduran->id,
        ]));

        $response->assertOk();
        $response->assertJsonFragment(['nama_ruangan' => 'Ruang 1']);
        $response->assertJsonFragment(['nama_ruangan' => 'Ruang 2']);
        $response->assertJsonMissing(['nama_ruangan' => 'Ruang A Candi']);
    }

    // 3. Test API Endpoint: POST /admin/jadwal/check-room-conflict
    public function test_api_check_room_conflict_detects_overlap(): void
    {
        $admin = $this->getAdminUser();

        // Existing schedule in Ruang 1: 10:00 - 12:00
        $existing = Jadwal::create([
            'nama_kelas' => 'MO-EXISTING-101',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-12-10',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'program_id' => $this->programOffice->id,
            'tentor_id' => $this->tentor1->id,
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'ruangan' => $this->ruang1->nama_ruangan,
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Check overlapping time in Ruang 1: 11:00 - 13:00 -> has conflict
        $resConflict = $this->actingAs($admin)->postJson(route('admin.jadwal.check-room-conflict'), [
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'tanggal' => '2026-12-10',
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
        ]);

        $resConflict->assertOk();
        $resConflict->assertJson([
            'has_conflict' => true,
        ]);
        $resConflict->assertJsonFragment(['nama_kelas' => 'MO-EXISTING-101']);

        // Check non-overlapping time in Ruang 1: 13:00 - 15:00 -> no conflict
        $resNoConflict = $this->actingAs($admin)->postJson(route('admin.jadwal.check-room-conflict'), [
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'tanggal' => '2026-12-10',
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:00',
        ]);

        $resNoConflict->assertOk();
        $resNoConflict->assertJson([
            'has_conflict' => false,
            'conflicts' => [],
        ]);

        // Check with exclude_id equal to existing schedule -> no conflict with self
        $resExclude = $this->actingAs($admin)->postJson(route('admin.jadwal.check-room-conflict'), [
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'tanggal' => '2026-12-10',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'exclude_id' => $existing->id,
        ]);

        $resExclude->assertOk();
        $resExclude->assertJson([
            'has_conflict' => false,
        ]);
    }

    // 4. Test Room Conflict without force_room (Soft Warning Default = Error)
    public function test_room_conflict_without_force_room_is_blocked(): void
    {
        $admin = $this->getAdminUser();

        Jadwal::create([
            'nama_kelas' => 'MO-EXISTING-201',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-12-15',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'program_id' => $this->programOffice->id,
            'tentor_id' => $this->tentor1->id,
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'ruangan' => $this->ruang1->nama_ruangan,
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Attempt same room with different tentor without force_room
        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => $this->cabangBuduran->id,
            'program_id' => $this->programCoding->id,
            'tentor_id' => $this->tentor2->id,
            'nama_kelas' => 'WEB-OVERLAP-FAIL',
            'jenis_kelas' => 'rombel',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-12-15',
            'jam_mulai' => '14:00',
            'jam_selesai' => '16:00',
            'ruangan_id' => $this->ruang1->id,
            'pertemuan' => 1,
            'force_room' => 0,
        ]);

        $response->assertSessionHasErrors(['ruangan_id']);
    }

    // 5. Test Room Conflict with force_room (Allowed with Audit Note)
    public function test_room_conflict_with_force_room_succeeds_and_logs_override_audit(): void
    {
        $admin = $this->getAdminUser();

        Jadwal::create([
            'nama_kelas' => 'MO-EXISTING-301',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-12-20',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'program_id' => $this->programOffice->id,
            'tentor_id' => $this->tentor1->id,
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'ruangan' => $this->ruang1->nama_ruangan,
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Create overlapping schedule with different tentor AND force_room = 1
        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => $this->cabangBuduran->id,
            'program_id' => $this->programCoding->id,
            'tentor_id' => $this->tentor2->id,
            'nama_kelas' => 'WEB-OVERRIDE-SUCCESS',
            'jenis_kelas' => 'rombel',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-12-20',
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan_id' => $this->ruang1->id,
            'pertemuan' => 1,
            'catatan' => 'Materi Belajar CSS Grid',
            'force_room' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        // Verify schedule is saved
        $newJadwal = Jadwal::where('nama_kelas', 'WEB-OVERRIDE-SUCCESS')->first();
        $this->assertNotNull($newJadwal);
        $this->assertEquals($this->ruang1->id, $newJadwal->ruangan_id);

        // Verify audit note is recorded in catatan
        $this->assertStringContainsString('[OVERRIDE]', $newJadwal->catatan);
        $this->assertStringContainsString('MO-EXISTING-301', $newJadwal->catatan);
        $this->assertStringContainsString('Materi Belajar CSS Grid', $newJadwal->catatan);
    }

    // 6. Test Tentor Conflict CANNOT be bypassed even with force_room = 1 (Hard Block)
    public function test_tentor_conflict_cannot_be_overridden_by_force_room(): void
    {
        $admin = $this->getAdminUser();

        Jadwal::create([
            'nama_kelas' => 'MO-TENTOR-BUSY',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-12-22',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'program_id' => $this->programOffice->id,
            'tentor_id' => $this->tentor1->id,
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'ruangan' => $this->ruang1->nama_ruangan,
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Attempt same tentor at overlapping time in a different room with force_room = 1
        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => $this->cabangBuduran->id,
            'program_id' => $this->programOffice->id,
            'tentor_id' => $this->tentor1->id,
            'nama_kelas' => 'MO-TENTOR-CONFLICT-ATTEMPT',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-12-22',
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
            'ruangan_id' => $this->ruang2->id,
            'pertemuan' => 2,
            'force_room' => 1,
        ]);

        $response->assertSessionHasErrors(['tentor_id']);
        $this->assertDatabaseMissing('jadwal', ['nama_kelas' => 'MO-TENTOR-CONFLICT-ATTEMPT']);
    }

    // 7. Test Visibility of [OVERRIDE] note in detail page (Admin vs Superadmin)
    public function test_override_note_visibility_admin_vs_superadmin(): void
    {
        $admin = $this->getAdminUser();
        $superadmin = $this->getSuperadminUser();

        $jadwal = Jadwal::create([
            'nama_kelas' => 'AUDIT-VISIBILITY-TEST',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => '2026-12-28',
            'jam_mulai' => '14:00:00',
            'jam_selesai' => '16:00:00',
            'program_id' => $this->programOffice->id,
            'tentor_id' => $this->tentor1->id,
            'cabang_id' => $this->cabangBuduran->id,
            'ruangan_id' => $this->ruang1->id,
            'ruangan' => $this->ruang1->nama_ruangan,
            'pertemuan' => 1,
            'status' => 'terjadwal',
            'catatan' => "[OVERRIDE] Dijadwalkan meskipun ada konflik ruangan dengan kelas MO-01 pada jam 14:00 - 16:00.\nCatatan tambahan guru.",
        ]);

        // Admin view: should NOT see [OVERRIDE]
        $resAdmin = $this->actingAs($admin)->get(route('admin.jadwal.show', $jadwal));
        $resAdmin->assertOk();
        $resAdmin->assertDontSee('[OVERRIDE]');
        $resAdmin->assertDontSee('Catatan Audit Superadmin');
        $resAdmin->assertSee('Catatan tambahan guru.');

        // Superadmin view: should see [OVERRIDE] and audit box
        $resSuperadmin = $this->actingAs($superadmin)->get(route('admin.jadwal.show', $jadwal));
        $resSuperadmin->assertOk();
        $resSuperadmin->assertSee('[OVERRIDE]');
        $resSuperadmin->assertSee('Catatan Audit Superadmin: Force-Override Konflik Ruangan');
        $resSuperadmin->assertSee('Catatan tambahan guru.');
    }
}

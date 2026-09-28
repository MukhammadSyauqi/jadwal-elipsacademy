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

class SuperadminRuanganCrudTest extends TestCase
{
    use DatabaseTransactions;

    protected Cabang $cabang1;
    protected Cabang $cabang2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cabang1 = Cabang::firstOrCreate(
            ['nama_cabang' => 'Buduran'],
            ['alamat' => 'Jl. Raya Buduran No. 12', 'status' => 'aktif']
        );

        $this->cabang2 = Cabang::firstOrCreate(
            ['nama_cabang' => 'Candi'],
            ['alamat' => 'Jl. Raya Candi No. 45', 'status' => 'aktif']
        );
    }

    protected function getSuperadmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.ruangan.test@elipsacademy.com'],
            [
                'nama' => 'Superadmin Ruangan Tester',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
    }

    protected function getAdmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.ruangan.test@elipsacademy.com'],
            [
                'nama' => 'Admin Ruangan Tester',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'cabang_id' => $this->cabang1->id,
            ]
        );
    }

    // 1. Role Protection Tests
    public function test_guest_is_redirected_to_login_for_all_ruangan_routes(): void
    {
        $ruangan = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Guest Test',
            'kapasitas' => 15,
            'status' => 'aktif',
        ]);

        $this->get(route('superadmin.ruangan.index'))->assertRedirect('/login');
        $this->post(route('superadmin.ruangan.store'), [])->assertRedirect('/login');
        $this->put(route('superadmin.ruangan.update', $ruangan), [])->assertRedirect('/login');
        $this->post(route('superadmin.ruangan.toggle-status', $ruangan))->assertRedirect('/login');
        $this->delete(route('superadmin.ruangan.destroy', $ruangan))->assertRedirect('/login');
    }

    public function test_admin_cannot_access_superadmin_ruangan_routes(): void
    {
        $admin = $this->getAdmin();

        $ruangan = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Admin Test',
            'kapasitas' => 15,
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)->get(route('superadmin.ruangan.index'))->assertForbidden();
        $this->actingAs($admin)->post(route('superadmin.ruangan.store'), [])->assertForbidden();
        $this->actingAs($admin)->put(route('superadmin.ruangan.update', $ruangan), [])->assertForbidden();
        $this->actingAs($admin)->post(route('superadmin.ruangan.toggle-status', $ruangan))->assertForbidden();
        $this->actingAs($admin)->delete(route('superadmin.ruangan.destroy', $ruangan))->assertForbidden();
    }

    // 2. View & Filter Tests
    public function test_superadmin_can_view_ruangan_index(): void
    {
        $superadmin = $this->getSuperadmin();

        $ruangan = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Komputer Alpha',
            'kapasitas' => 20,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.ruangan.index'));
        $response->assertOk();
        $response->assertSee('Master Data Ruangan');
        $response->assertSee('Lab Komputer Alpha');
        $response->assertSee('Buduran');
        $response->assertSee('Total Ruangan');
    }

    public function test_superadmin_can_filter_ruangan_by_cabang_and_status(): void
    {
        $superadmin = $this->getSuperadmin();

        $r1 = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab 101 Filter Test',
            'kapasitas' => 10,
            'status' => 'aktif',
        ]);

        $r2 = Ruangan::create([
            'cabang_id' => $this->cabang2->id,
            'nama_ruangan' => 'Lab 202 Filter Test',
            'kapasitas' => 20,
            'status' => 'nonaktif',
        ]);

        // Filter by cabang1
        $resCabang = $this->actingAs($superadmin)->get(route('superadmin.ruangan.index', [
            'cabang_id' => $this->cabang1->id,
        ]));
        $resCabang->assertOk();
        $resCabang->assertSee('Lab 101 Filter Test');
        $resCabang->assertDontSee('Lab 202 Filter Test');

        // Filter by status nonaktif
        $resStatus = $this->actingAs($superadmin)->get(route('superadmin.ruangan.index', [
            'status' => 'nonaktif',
        ]));
        $resStatus->assertOk();
        $resStatus->assertSee('Lab 202 Filter Test');

        // Filter by search query
        $resSearch = $this->actingAs($superadmin)->get(route('superadmin.ruangan.index', [
            'q' => '101 Filter',
        ]));
        $resSearch->assertOk();
        $resSearch->assertSee('Lab 101 Filter Test');
        $resSearch->assertDontSee('Lab 202 Filter Test');
    }

    // 3. Create Tests
    public function test_superadmin_can_create_ruangan_with_valid_data(): void
    {
        $superadmin = $this->getSuperadmin();

        $payload = [
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Pemrograman C',
            'kapasitas' => 25,
            'status' => 'aktif',
        ];

        $response = $this->actingAs($superadmin)->post(route('superadmin.ruangan.store'), $payload);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ruangan', [
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Pemrograman C',
            'kapasitas' => 25,
            'status' => 'aktif',
        ]);
    }

    public function test_create_ruangan_validation_requires_cabang_and_nama(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->post(route('superadmin.ruangan.store'), []);
        $response->assertSessionHasErrors(['cabang_id', 'nama_ruangan']);
    }

    public function test_nama_ruangan_must_be_unique_within_same_cabang(): void
    {
        $superadmin = $this->getSuperadmin();

        Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Ruang Teori 1',
            'kapasitas' => 30,
            'status' => 'aktif',
        ]);

        // Attempt duplicate in same cabang -> should fail validation
        $response = $this->actingAs($superadmin)->post(route('superadmin.ruangan.store'), [
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Ruang Teori 1',
            'kapasitas' => 20,
            'status' => 'aktif',
        ]);
        $response->assertSessionHasErrors('nama_ruangan');

        // Creating same name in different cabang -> should succeed
        $responseDiffCabang = $this->actingAs($superadmin)->post(route('superadmin.ruangan.store'), [
            'cabang_id' => $this->cabang2->id,
            'nama_ruangan' => 'Ruang Teori 1',
            'kapasitas' => 20,
            'status' => 'aktif',
        ]);
        $responseDiffCabang->assertRedirect();
        $responseDiffCabang->assertSessionHasNoErrors();
        $this->assertDatabaseHas('ruangan', [
            'cabang_id' => $this->cabang2->id,
            'nama_ruangan' => 'Ruang Teori 1',
        ]);
    }

    // 4. Update Tests
    public function test_superadmin_can_update_ruangan(): void
    {
        $superadmin = $this->getSuperadmin();

        $ruangan = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Lama',
            'kapasitas' => 12,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($superadmin)->put(route('superadmin.ruangan.update', $ruangan), [
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Baru Diperbarui',
            'kapasitas' => 18,
            'status' => 'nonaktif',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ruangan', [
            'id' => $ruangan->id,
            'nama_ruangan' => 'Lab Baru Diperbarui',
            'kapasitas' => 18,
            'status' => 'nonaktif',
        ]);
    }

    public function test_update_allows_keeping_same_name_for_same_ruangan(): void
    {
        $superadmin = $this->getSuperadmin();

        $ruangan = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Tetap Sama',
            'kapasitas' => 15,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($superadmin)->put(route('superadmin.ruangan.update', $ruangan), [
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Lab Tetap Sama',
            'kapasitas' => 22,
            'status' => 'aktif',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('ruangan', [
            'id' => $ruangan->id,
            'kapasitas' => 22,
        ]);
    }

    // 5. Toggle Status Tests
    public function test_superadmin_can_toggle_ruangan_status(): void
    {
        $superadmin = $this->getSuperadmin();

        $ruangan = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Ruang Toggle Status',
            'kapasitas' => 10,
            'status' => 'aktif',
        ]);

        // Toggle from aktif -> nonaktif
        $response1 = $this->actingAs($superadmin)->post(route('superadmin.ruangan.toggle-status', $ruangan));
        $response1->assertRedirect();
        $this->assertDatabaseHas('ruangan', ['id' => $ruangan->id, 'status' => 'nonaktif']);

        // Toggle from nonaktif -> aktif
        $response2 = $this->actingAs($superadmin)->post(route('superadmin.ruangan.toggle-status', $ruangan));
        $response2->assertRedirect();
        $this->assertDatabaseHas('ruangan', ['id' => $ruangan->id, 'status' => 'aktif']);
    }

    // 6. Delete & Relational Guard Tests
    public function test_superadmin_can_delete_ruangan_without_jadwal(): void
    {
        $superadmin = $this->getSuperadmin();

        $ruangan = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Ruang Siap Hapus',
            'kapasitas' => 10,
            'status' => 'nonaktif',
        ]);

        $response = $this->actingAs($superadmin)->delete(route('superadmin.ruangan.destroy', $ruangan));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('ruangan', ['id' => $ruangan->id]);
    }

    public function test_superadmin_cannot_delete_ruangan_with_associated_jadwals(): void
    {
        $superadmin = $this->getSuperadmin();

        $program = Program::firstOrCreate(
            ['nama_program' => 'Office Ruangan Test'],
            ['kategori' => 'Office', 'status' => 'aktif']
        );

        $tentor = Tentor::firstOrCreate(
            ['nama' => 'Tentor Ruangan Guard Test'],
            ['no_hp' => '08999999999', 'keahlian' => 'Office', 'status' => 'aktif']
        );

        $ruangan = Ruangan::create([
            'cabang_id' => $this->cabang1->id,
            'nama_ruangan' => 'Ruang Terjadwal Guard',
            'kapasitas' => 15,
            'status' => 'aktif',
        ]);

        $jadwal = Jadwal::create([
            'nama_kelas' => 'Kelas Office Pagi',
            'jenis_kelas' => 'rombel',
            'tanggal' => now()->toDateString(),
            'program_id' => $program->id,
            'tentor_id' => $tentor->id,
            'cabang_id' => $this->cabang1->id,
            'ruangan_id' => $ruangan->id,
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:30',
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($superadmin)->delete(route('superadmin.ruangan.destroy', $ruangan));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Confirm ruangan is NOT deleted
        $this->assertDatabaseHas('ruangan', ['id' => $ruangan->id]);
    }
}

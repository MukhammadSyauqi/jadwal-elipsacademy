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

class SuperadminMasterDataTest extends TestCase
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

    protected function getSuperadmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.master.test@elipsacademy.com'],
            [
                'nama' => 'Superadmin Master Tester',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
    }

    protected function getAdmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.master.test@elipsacademy.com'],
            [
                'nama' => 'Admin Master Tester',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }

    // 1. Role Protection Tests
    public function test_guest_is_redirected_to_login_for_all_master_routes(): void
    {
        $this->get(route('superadmin.cabang.index'))->assertRedirect('/login');
        $this->get(route('superadmin.program.index'))->assertRedirect('/login');
        $this->get(route('superadmin.tentor.index'))->assertRedirect('/login');
    }

    public function test_admin_cannot_access_superadmin_master_routes(): void
    {
        $admin = $this->getAdmin();

        $this->actingAs($admin)->get(route('superadmin.cabang.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('superadmin.program.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('superadmin.tentor.index'))->assertForbidden();
    }

    // 2. Cabang CRUD Tests
    public function test_superadmin_can_view_cabang_index(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->get(route('superadmin.cabang.index'));
        $response->assertOk();
        $response->assertSee('Buduran');
        $response->assertSee('Candi');
        $response->assertSee('Master Data Cabang');
    }

    public function test_superadmin_can_create_and_update_cabang(): void
    {
        $superadmin = $this->getSuperadmin();

        // Create
        $response = $this->actingAs($superadmin)->post(route('superadmin.cabang.store'), [
            'nama_cabang' => 'Porong Test Branch',
            'alamat' => 'Jl. Raya Porong KM 35',
            'status' => 'aktif',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('cabang', ['nama_cabang' => 'Porong Test Branch']);

        $newCabang = Cabang::where('nama_cabang', 'Porong Test Branch')->first();

        // Update
        $updateResponse = $this->actingAs($superadmin)->put(route('superadmin.cabang.update', $newCabang), [
            'nama_cabang' => 'Porong Branch Updated',
            'alamat' => 'Jl. Porong Baru No. 10',
            'status' => 'nonaktif',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('cabang', [
            'id' => $newCabang->id,
            'nama_cabang' => 'Porong Branch Updated',
            'status' => 'nonaktif',
        ]);
    }

    public function test_superadmin_can_toggle_cabang_status(): void
    {
        $superadmin = $this->getSuperadmin();
        $cabang = Cabang::create(['nama_cabang' => 'Toggle Cabang Test', 'status' => 'aktif']);

        // Toggle to nonaktif
        $this->actingAs($superadmin)->post(route('superadmin.cabang.toggle-status', $cabang));
        $this->assertEquals('nonaktif', $cabang->fresh()->status);

        // Toggle back to aktif
        $this->actingAs($superadmin)->post(route('superadmin.cabang.toggle-status', $cabang));
        $this->assertEquals('aktif', $cabang->fresh()->status);
    }

    public function test_cabang_with_schedules_cannot_be_hard_deleted(): void
    {
        $superadmin = $this->getSuperadmin();
        $cabang = Cabang::create(['nama_cabang' => 'Locked Branch Test', 'status' => 'aktif']);
        
        // Create an attached schedule
        Jadwal::create([
            'cabang_id' => $cabang->id,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'Kelas Protected',
            'jenis_kelas' => 'private',
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Attempt delete
        $response = $this->actingAs($superadmin)->delete(route('superadmin.cabang.destroy', $cabang));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('cabang', ['id' => $cabang->id]);
    }

    public function test_cabang_without_schedules_can_be_hard_deleted(): void
    {
        $superadmin = $this->getSuperadmin();
        $cabang = Cabang::create(['nama_cabang' => 'Unused Branch Test', 'status' => 'aktif']);

        $response = $this->actingAs($superadmin)->delete(route('superadmin.cabang.destroy', $cabang));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('cabang', ['id' => $cabang->id]);
    }

    // 3. Program Kursus CRUD Tests
    public function test_superadmin_can_view_and_manage_program(): void
    {
        $superadmin = $this->getSuperadmin();

        // Index
        $response = $this->actingAs($superadmin)->get(route('superadmin.program.index'));
        $response->assertOk();
        $response->assertSee('Web Programming');

        // Store
        $createResponse = $this->actingAs($superadmin)->post(route('superadmin.program.store'), [
            'nama_program' => 'Data Science AI',
            'kategori' => 'Data',
            'status' => 'aktif',
        ]);
        $createResponse->assertRedirect();
        $this->assertDatabaseHas('program', ['nama_program' => 'Data Science AI']);

        $program = Program::where('nama_program', 'Data Science AI')->first();

        // Update
        $this->actingAs($superadmin)->put(route('superadmin.program.update', $program), [
            'nama_program' => 'Data Science AI Pro',
            'kategori' => 'AI & Data',
            'status' => 'aktif',
        ]);
        $this->assertDatabaseHas('program', ['nama_program' => 'Data Science AI Pro']);

        // Toggle
        $this->actingAs($superadmin)->post(route('superadmin.program.toggle-status', $program));
        $this->assertEquals('nonaktif', $program->fresh()->status);

        // Delete unused
        $this->actingAs($superadmin)->delete(route('superadmin.program.destroy', $program));
        $this->assertDatabaseMissing('program', ['id' => $program->id]);
    }

    public function test_program_with_schedules_cannot_be_hard_deleted(): void
    {
        $superadmin = $this->getSuperadmin();
        $program = Program::create(['nama_program' => 'Protected Program Test', 'kategori' => 'Test', 'status' => 'aktif']);

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => $program->id,
            'tentor_id' => 1,
            'nama_kelas' => 'Kelas Protected Program',
            'jenis_kelas' => 'private',
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($superadmin)->delete(route('superadmin.program.destroy', $program));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('program', ['id' => $program->id]);
    }

    // 4. Tentor CRUD Tests
    public function test_superadmin_can_view_and_manage_tentor(): void
    {
        $superadmin = $this->getSuperadmin();

        // Index
        $response = $this->actingAs($superadmin)->get(route('superadmin.tentor.index'));
        $response->assertOk();
        $response->assertSee('Andi Pratama');

        // Store
        $createResponse = $this->actingAs($superadmin)->post(route('superadmin.tentor.store'), [
            'nama' => 'Citra Dewi, S.Kom',
            'no_hp' => '081999888777',
            'keahlian' => 'UI/UX Design, Figma',
            'status' => 'aktif',
        ]);
        $createResponse->assertRedirect();
        $this->assertDatabaseHas('tentor', ['nama' => 'Citra Dewi, S.Kom']);

        $tentor = Tentor::where('nama', 'Citra Dewi, S.Kom')->first();

        // Update
        $this->actingAs($superadmin)->put(route('superadmin.tentor.update', $tentor), [
            'nama' => 'Citra Dewi, M.Kom',
            'no_hp' => '081999888777',
            'keahlian' => 'UI/UX Design Lead',
            'status' => 'aktif',
        ]);
        $this->assertDatabaseHas('tentor', ['nama' => 'Citra Dewi, M.Kom']);

        // Toggle
        $this->actingAs($superadmin)->post(route('superadmin.tentor.toggle-status', $tentor));
        $this->assertEquals('nonaktif', $tentor->fresh()->status);

        // Delete unused
        $this->actingAs($superadmin)->delete(route('superadmin.tentor.destroy', $tentor));
        $this->assertDatabaseMissing('tentor', ['id' => $tentor->id]);
    }

    public function test_tentor_with_schedules_cannot_be_hard_deleted(): void
    {
        $superadmin = $this->getSuperadmin();
        $tentor = Tentor::create(['nama' => 'Protected Tentor Test', 'status' => 'aktif']);

        Jadwal::create([
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => $tentor->id,
            'nama_kelas' => 'Kelas Protected Tentor',
            'jenis_kelas' => 'private',
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($superadmin)->delete(route('superadmin.tentor.destroy', $tentor));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('tentor', ['id' => $tentor->id]);
    }

    // 5. Inactive Master Data Dropdown Integrity
    public function test_inactive_master_data_cannot_be_used_for_new_schedule(): void
    {
        $admin = $this->getAdmin();

        $inactiveCabang = Cabang::create(['nama_cabang' => 'Cabang Inactive', 'status' => 'nonaktif']);
        $inactiveProgram = Program::create(['nama_program' => 'Program Inactive', 'status' => 'nonaktif']);
        $inactiveTentor = Tentor::create(['nama' => 'Tentor Inactive', 'status' => 'nonaktif']);

        // 1. Check create view does not include inactive items
        $viewResponse = $this->actingAs($admin)->get(route('admin.jadwal.create'));
        $viewResponse->assertDontSee('Cabang Inactive');
        $viewResponse->assertDontSee('Program Inactive');
        $viewResponse->assertDontSee('Tentor Inactive');

        // 2. Attempt to store schedule with inactive cabang -> should fail validation
        $storeResponse = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => $inactiveCabang->id,
            'program_id' => 1,
            'tentor_id' => 1,
            'nama_kelas' => 'Kelas Inactive Test',
            'jenis_kelas' => 'private',
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
        ]);
        $storeResponse->assertSessionHasErrors('cabang_id');

        // 3. Attempt to store schedule with inactive program -> should fail validation
        $storeResponse2 = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => 1,
            'program_id' => $inactiveProgram->id,
            'tentor_id' => 1,
            'nama_kelas' => 'Kelas Inactive Test 2',
            'jenis_kelas' => 'private',
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
        ]);
        $storeResponse2->assertSessionHasErrors('program_id');

        // 4. Attempt to store schedule with inactive tentor -> should fail validation
        $storeResponse3 = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'cabang_id' => 1,
            'program_id' => 1,
            'tentor_id' => $inactiveTentor->id,
            'nama_kelas' => 'Kelas Inactive Test 3',
            'jenis_kelas' => 'private',
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'ruangan' => 'Ruang 1',
            'pertemuan' => 1,
        ]);
        $storeResponse3->assertSessionHasErrors('tentor_id');
    }
}

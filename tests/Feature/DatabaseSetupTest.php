<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Tentor;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSetupTest extends TestCase
{
    /**
     * Test that all 5 required tables exist in database.
     */
    public function test_all_five_tables_exist(): void
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('cabang'));
        $this->assertTrue(Schema::hasTable('program'));
        $this->assertTrue(Schema::hasTable('tentor'));
        $this->assertTrue(Schema::hasTable('jadwal'));
    }

    /**
     * Test table columns match specification.
     */
    public function test_table_columns_match_specification(): void
    {
        $this->assertTrue(Schema::hasColumns('users', ['id', 'nama', 'email', 'password', 'role', 'created_at', 'updated_at']));
        $this->assertTrue(Schema::hasColumns('cabang', ['id', 'nama_cabang', 'alamat', 'status', 'created_at', 'updated_at']));
        $this->assertTrue(Schema::hasColumns('program', ['id', 'nama_program', 'kategori', 'status', 'created_at', 'updated_at']));
        $this->assertTrue(Schema::hasColumns('tentor', ['id', 'nama', 'no_hp', 'keahlian', 'status', 'created_at', 'updated_at']));
        $this->assertTrue(Schema::hasColumns('jadwal', [
            'id', 'cabang_id', 'program_id', 'tentor_id', 'nama_kelas',
            'jenis_kelas', 'tanggal', 'jam_mulai', 'jam_selesai',
            'ruangan', 'pertemuan', 'status', 'catatan', 'created_at', 'updated_at'
        ]));
    }

    /**
     * Test seeder data counts and values.
     */
    public function test_seeder_data_integrity(): void
    {
        $this->assertGreaterThanOrEqual(2, User::count());
        $this->assertGreaterThanOrEqual(2, Cabang::count());
        $this->assertGreaterThanOrEqual(3, Program::count());
        $this->assertGreaterThanOrEqual(3, Tentor::count());
        $this->assertGreaterThanOrEqual(2, Jadwal::count());

        $superadmin = User::where('email', 'superadmin@elipsacademy.com')->first();
        $this->assertNotNull($superadmin);
        $this->assertEquals('superadmin', $superadmin->role);
        $this->assertTrue($superadmin->isSuperAdmin());
        $this->assertFalse($superadmin->isAdmin());
        $this->assertTrue(Hash::check('password', $superadmin->password));

        $admin = User::where('email', 'admin.candi@elipsacademy.com')->first();
        $this->assertNotNull($admin);
        $this->assertEquals('admin', $admin->role);
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isSuperAdmin());
        $this->assertTrue(Hash::check('password', $admin->password));
    }

    /**
     * Test Eloquent relationships between Jadwal and Master tables.
     */
    public function test_eloquent_relationships(): void
    {
        $jadwal = Jadwal::first();
        $this->assertNotNull($jadwal);

        // BelongsTo tests
        $this->assertInstanceOf(Cabang::class, $jadwal->cabang);
        $this->assertInstanceOf(Program::class, $jadwal->program);
        $this->assertInstanceOf(Tentor::class, $jadwal->tentor);

        // HasMany tests
        $cabang = $jadwal->cabang;
        $this->assertTrue($cabang->jadwals->contains($jadwal));

        $program = $jadwal->program;
        $this->assertTrue($program->jadwals->contains($jadwal));

        $tentor = $jadwal->tentor;
        $this->assertTrue($tentor->jadwals->contains($jadwal));
    }

    /**
     * Test RESTRICT constraint on foreign keys.
     */
    public function test_foreign_key_restrict_prevents_deleting_referenced_master(): void
    {
        $jadwal = Jadwal::first();
        $cabang = $jadwal->cabang;

        $this->expectException(QueryException::class);
        $cabang->delete();
    }
}

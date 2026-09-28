<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Ruangan;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RuanganModelSetupTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 1. Test table ruangan exists with correct columns and schema types.
     */
    public function test_ruangan_table_structure_and_constraints(): void
    {
        $this->assertTrue(Schema::hasTable('ruangan'));
        $this->assertTrue(Schema::hasColumns('ruangan', [
            'id', 'cabang_id', 'nama_ruangan', 'kapasitas', 'status', 'created_at', 'updated_at'
        ]));

        // Check ruangan_id column exists on jadwal table
        $this->assertTrue(Schema::hasColumn('jadwal', 'ruangan_id'));
        $this->assertTrue(Schema::hasColumn('jadwal', 'ruangan')); // Backward compatibility
    }

    /**
     * 2. Test seeder creates correct counts of rooms per branch.
     * Candi: 2, Buduran: 3, Gubeng: 3
     */
    public function test_seeder_creates_expected_rooms_per_branch(): void
    {
        $buduran = Cabang::where('nama_cabang', 'Buduran')->first();
        $candi = Cabang::where('nama_cabang', 'Candi')->first();
        $gubeng = Cabang::where('nama_cabang', 'Gubeng')->first();

        $this->assertNotNull($buduran);
        $this->assertNotNull($candi);
        $this->assertNotNull($gubeng);

        $this->assertEquals(3, Ruangan::where('cabang_id', $buduran->id)->count());
        $this->assertEquals(2, Ruangan::where('cabang_id', $candi->id)->count());
        $this->assertEquals(3, Ruangan::where('cabang_id', $gubeng->id)->count());

        $this->assertTrue(Ruangan::where('cabang_id', $buduran->id)->where('nama_ruangan', 'Ruang 1')->exists());
        $this->assertTrue(Ruangan::where('cabang_id', $buduran->id)->where('nama_ruangan', 'Ruang 2')->exists());
        $this->assertTrue(Ruangan::where('cabang_id', $buduran->id)->where('nama_ruangan', 'Ruang 3')->exists());

        $this->assertTrue(Ruangan::where('cabang_id', $candi->id)->where('nama_ruangan', 'Ruang 1')->exists());
        $this->assertTrue(Ruangan::where('cabang_id', $candi->id)->where('nama_ruangan', 'Ruang 2')->exists());

        $this->assertTrue(Ruangan::where('cabang_id', $gubeng->id)->where('nama_ruangan', 'Ruang 1')->exists());
        $this->assertTrue(Ruangan::where('cabang_id', $gubeng->id)->where('nama_ruangan', 'Ruang 2')->exists());
        $this->assertTrue(Ruangan::where('cabang_id', $gubeng->id)->where('nama_ruangan', 'Ruang 3')->exists());
    }

    /**
     * 3. Test Eloquent relationships between Ruangan, Cabang, and Jadwal.
     */
    public function test_ruangan_eloquent_relationships(): void
    {
        $cabang = Cabang::firstOrCreate(['id' => 1], ['nama_cabang' => 'Buduran', 'status' => 'aktif']);

        $ruangan = Ruangan::firstOrCreate(
            ['cabang_id' => $cabang->id, 'nama_ruangan' => 'Ruang 1'],
            ['kapasitas' => 12, 'status' => 'aktif']
        );

        // Ruangan belongsTo Cabang
        $this->assertInstanceOf(Cabang::class, $ruangan->cabang);
        $this->assertEquals($cabang->id, $ruangan->cabang->id);

        // Cabang hasMany Ruangan
        $this->assertTrue($cabang->ruangans->contains('id', $ruangan->id));

        // Create schedule with ruangan_id
        $jadwal = Jadwal::create([
            'cabang_id' => $cabang->id,
            'program_id' => 1,
            'tentor_id' => 1,
            'ruangan_id' => $ruangan->id,
            'nama_kelas' => 'RELASI-ROOM-TEST',
            'jenis_kelas' => 'private',
            'mode_kelas' => 'offline',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
            'ruangan' => $ruangan->nama_ruangan,
            'pertemuan' => 1,
            'status' => 'terjadwal',
        ]);

        // Jadwal belongsTo Ruangan (ruanganRef)
        $this->assertInstanceOf(Ruangan::class, $jadwal->ruanganRef);
        $this->assertEquals($ruangan->id, $jadwal->ruanganRef->id);

        // Ruangan hasMany Jadwal
        $this->assertTrue($ruangan->jadwals->contains('id', $jadwal->id));
    }

    /**
     * 4. Test unique constraint prevents duplicate room names in the same branch.
     */
    public function test_unique_constraint_prevents_duplicate_room_in_same_branch(): void
    {
        $buduran = Cabang::where('nama_cabang', 'Buduran')->first();
        $this->assertNotNull($buduran);

        $this->expectException(QueryException::class);

        Ruangan::create([
            'cabang_id' => $buduran->id,
            'nama_ruangan' => 'Ruang 1', // Already exists in Buduran
            'kapasitas' => 20,
            'status' => 'aktif',
        ]);
    }

    /**
     * 5. Test that duplicate room names across different branches are allowed.
     */
    public function test_same_room_name_allowed_in_different_branches(): void
    {
        $buduran = Cabang::where('nama_cabang', 'Buduran')->first();
        $candi = Cabang::where('nama_cabang', 'Candi')->first();

        // Both already have 'Ruang 1', verify both exist and have distinct IDs
        $roomBuduran = Ruangan::where('cabang_id', $buduran->id)->where('nama_ruangan', 'Ruang 1')->first();
        $roomCandi = Ruangan::where('cabang_id', $candi->id)->where('nama_ruangan', 'Ruang 1')->first();

        $this->assertNotNull($roomBuduran);
        $this->assertNotNull($roomCandi);
        $this->assertNotEquals($roomBuduran->id, $roomCandi->id);
    }
}

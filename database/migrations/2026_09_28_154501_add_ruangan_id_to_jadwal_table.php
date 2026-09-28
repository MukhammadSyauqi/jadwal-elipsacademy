<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreignId('ruangan_id')
                ->nullable()
                ->after('ruangan')
                ->constrained('ruangan')
                ->nullOnDelete();
        });

        // Data migration: match existing schedules by (cabang_id, ruangan) or create new room record
        $jadwals = DB::table('jadwal')->whereNotNull('ruangan')->get();
        foreach ($jadwals as $jadwal) {
            $namaRuangan = trim((string) $jadwal->ruangan);
            if ($namaRuangan === '') {
                continue;
            }

            $ruangan = DB::table('ruangan')
                ->where('cabang_id', $jadwal->cabang_id)
                ->where('nama_ruangan', $namaRuangan)
                ->first();

            if (!$ruangan) {
                $now = now();
                $ruanganId = DB::table('ruangan')->insertGetId([
                    'cabang_id' => $jadwal->cabang_id,
                    'nama_ruangan' => $namaRuangan,
                    'kapasitas' => null,
                    'status' => 'aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                $ruanganId = $ruangan->id;
            }

            DB::table('jadwal')
                ->where('id', $jadwal->id)
                ->update(['ruangan_id' => $ruanganId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
            $table->dropColumn('ruangan_id');
        });
    }
};

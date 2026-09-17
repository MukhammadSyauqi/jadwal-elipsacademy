<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabang')->restrictOnDelete();
            $table->foreignId('program_id')->constrained('program')->restrictOnDelete();
            $table->foreignId('tentor_id')->constrained('tentor')->restrictOnDelete();
            $table->string('nama_kelas', 100);
            $table->enum('jenis_kelas', ['private', 'business', 'rombel']);
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 100)->nullable();
            $table->unsignedInteger('pertemuan')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('tanggal');
            $table->index('jenis_kelas');
            $table->index('status');
            $table->index(['tanggal', 'cabang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};

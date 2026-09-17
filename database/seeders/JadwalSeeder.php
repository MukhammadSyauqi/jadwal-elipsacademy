<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwalList = [
            [
                'cabang_id' => 2, // Candi
                'program_id' => 1, // Microsoft Office
                'tentor_id' => 1, // Budi
                'nama_kelas' => 'MO-001',
                'jenis_kelas' => 'private',
                'tanggal' => '2026-09-20',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00',
                'ruangan' => 'Ruang 1',
                'pertemuan' => 1,
                'status' => 'terjadwal',
                'catatan' => 'Kelas private Microsoft Office pertemuan 1',
            ],
            [
                'cabang_id' => 1, // Buduran
                'program_id' => 2, // Web Programming
                'tentor_id' => 2, // Andi
                'nama_kelas' => 'WP-001',
                'jenis_kelas' => 'rombel',
                'tanggal' => '2026-09-20',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
                'ruangan' => 'Ruang 2',
                'pertemuan' => 1,
                'status' => 'terjadwal',
                'catatan' => 'Kelas reguler rombel Web Programming pertemuan 1',
            ],
            [
                'cabang_id' => 2, // Candi
                'program_id' => 3, // Graphic Design
                'tentor_id' => 3, // Rina
                'nama_kelas' => 'GD-001',
                'jenis_kelas' => 'business',
                'tanggal' => '2026-09-21',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00',
                'ruangan' => 'Ruang Multimedia',
                'pertemuan' => 1,
                'status' => 'terjadwal',
                'catatan' => 'Kelas business Graphic Design',
            ],
        ];

        foreach ($jadwalList as $item) {
            Jadwal::updateOrCreate(
                [
                    'cabang_id' => $item['cabang_id'],
                    'program_id' => $item['program_id'],
                    'tentor_id' => $item['tentor_id'],
                    'nama_kelas' => $item['nama_kelas'],
                    'tanggal' => $item['tanggal'],
                    'jam_mulai' => $item['jam_mulai'],
                ],
                $item
            );
        }
    }
}

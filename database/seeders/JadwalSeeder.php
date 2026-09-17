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
        $today = \Carbon\Carbon::today()->toDateString();
        $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();

        $jadwalList = [
            // Cabang 1: Buduran - HARI INI
            [
                'cabang_id' => 1,
                'program_id' => 1,
                'tentor_id' => 1,
                'nama_kelas' => 'MO-001',
                'jenis_kelas' => 'private',
                'tanggal' => $today,
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00',
                'ruangan' => 'Ruang 1',
                'pertemuan' => 1,
                'status' => 'selesai',
                'catatan' => 'Microsoft Office Standard Pertemuan 1',
            ],
            [
                'cabang_id' => 1,
                'program_id' => 2,
                'tentor_id' => 2,
                'nama_kelas' => 'WP-002',
                'jenis_kelas' => 'rombel',
                'tanggal' => $today,
                'jam_mulai' => \Carbon\Carbon::now()->subMinutes(30)->format('H:i:00'),
                'jam_selesai' => \Carbon\Carbon::now()->addMinutes(90)->format('H:i:00'),
                'ruangan' => 'Lab Komputer B',
                'pertemuan' => 4,
                'status' => 'terjadwal',
                'catatan' => 'Web Programming - Frontend Foundation',
            ],
            [
                'cabang_id' => 1,
                'program_id' => 2,
                'tentor_id' => 2,
                'nama_kelas' => 'WP-001',
                'jenis_kelas' => 'rombel',
                'tanggal' => $today,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
                'ruangan' => 'Lab Komputer A',
                'pertemuan' => 5,
                'status' => 'terjadwal',
                'catatan' => 'Web Programming - Backend Laravel',
            ],
            [
                'cabang_id' => 1,
                'program_id' => 3,
                'tentor_id' => 3,
                'nama_kelas' => 'GD-001',
                'jenis_kelas' => 'business',
                'tanggal' => $today,
                'jam_mulai' => '15:30:00',
                'jam_selesai' => '17:30:00',
                'ruangan' => 'Studio Desain',
                'pertemuan' => 8,
                'status' => 'terjadwal',
                'catatan' => 'Graphic Design & Brand Visual Identity',
            ],
            [
                'cabang_id' => 1,
                'program_id' => 1,
                'tentor_id' => 1,
                'nama_kelas' => 'MO-002',
                'jenis_kelas' => 'private',
                'tanggal' => $today,
                'jam_mulai' => '18:30:00',
                'jam_selesai' => '20:30:00',
                'ruangan' => 'Ruang 2',
                'pertemuan' => 2,
                'status' => 'terjadwal',
                'catatan' => 'Microsoft Office Eksekutif & Dashboard Excel',
            ],
            [
                'cabang_id' => 1,
                'program_id' => 3,
                'tentor_id' => 3,
                'nama_kelas' => 'GD-003',
                'jenis_kelas' => 'rombel',
                'tanggal' => $today,
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00',
                'ruangan' => 'Studio 2',
                'pertemuan' => 3,
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan karena pemeliharaan ruangan studio',
            ],

            // Cabang 1: Buduran - BESOK
            [
                'cabang_id' => 1,
                'program_id' => 2,
                'tentor_id' => 2,
                'nama_kelas' => 'WP-003',
                'jenis_kelas' => 'rombel',
                'tanggal' => $tomorrow,
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00',
                'ruangan' => 'Lab Komputer A',
                'pertemuan' => 6,
                'status' => 'terjadwal',
                'catatan' => 'Web Programming Lanjutan',
            ],
            [
                'cabang_id' => 1,
                'program_id' => 1,
                'tentor_id' => 1,
                'nama_kelas' => 'MO-003',
                'jenis_kelas' => 'private',
                'tanggal' => $tomorrow,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
                'ruangan' => 'Ruang 1',
                'pertemuan' => 3,
                'status' => 'terjadwal',
                'catatan' => 'Microsoft Office Mahir',
            ],

            // Cabang 2: Candi - HARI INI
            [
                'cabang_id' => 2,
                'program_id' => 1,
                'tentor_id' => 1,
                'nama_kelas' => 'MO-CND-01',
                'jenis_kelas' => 'private',
                'tanggal' => $today,
                'jam_mulai' => '09:30:00',
                'jam_selesai' => '11:30:00',
                'ruangan' => 'Ruang A Candi',
                'pertemuan' => 2,
                'status' => 'selesai',
                'catatan' => 'Kelas Candi Pagi',
            ],
            [
                'cabang_id' => 2,
                'program_id' => 3,
                'tentor_id' => 3,
                'nama_kelas' => 'GD-CND-01',
                'jenis_kelas' => 'business',
                'tanggal' => $today,
                'jam_mulai' => '14:00:00',
                'jam_selesai' => '16:00:00',
                'ruangan' => 'Lab Multimedia Candi',
                'pertemuan' => 1,
                'status' => 'terjadwal',
                'catatan' => 'Kelas Candi Siang Desain',
            ],
            [
                'cabang_id' => 2,
                'program_id' => 2,
                'tentor_id' => 2,
                'nama_kelas' => 'WP-CND-01',
                'jenis_kelas' => 'rombel',
                'tanggal' => $today,
                'jam_mulai' => '19:00:00',
                'jam_selesai' => '21:00:00',
                'ruangan' => 'Lab IT Candi',
                'pertemuan' => 3,
                'status' => 'terjadwal',
                'catatan' => 'Kelas Candi Malam Web',
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

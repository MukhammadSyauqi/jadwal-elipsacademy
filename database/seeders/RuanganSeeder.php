<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangans = [
            // Cabang 1: Buduran (3 ruangan)
            [
                'cabang_id' => 1,
                'nama_ruangan' => 'Ruang 1',
                'kapasitas' => 12,
                'status' => 'aktif',
            ],
            [
                'cabang_id' => 1,
                'nama_ruangan' => 'Ruang 2',
                'kapasitas' => 12,
                'status' => 'aktif',
            ],
            [
                'cabang_id' => 1,
                'nama_ruangan' => 'Ruang 3',
                'kapasitas' => 10,
                'status' => 'aktif',
            ],

            // Cabang 2: Candi (2 ruangan)
            [
                'cabang_id' => 2,
                'nama_ruangan' => 'Ruang 1',
                'kapasitas' => 12,
                'status' => 'aktif',
            ],
            [
                'cabang_id' => 2,
                'nama_ruangan' => 'Ruang 2',
                'kapasitas' => 10,
                'status' => 'aktif',
            ],

            // Cabang 3: Gubeng (3 ruangan)
            [
                'cabang_id' => 3,
                'nama_ruangan' => 'Ruang 1',
                'kapasitas' => 10,
                'status' => 'aktif',
            ],
            [
                'cabang_id' => 3,
                'nama_ruangan' => 'Ruang 2',
                'kapasitas' => 10,
                'status' => 'aktif',
            ],
            [
                'cabang_id' => 3,
                'nama_ruangan' => 'Ruang 3',
                'kapasitas' => 15,
                'status' => 'aktif',
            ],
        ];

        foreach ($ruangans as $item) {
            Ruangan::updateOrCreate(
                [
                    'cabang_id' => $item['cabang_id'],
                    'nama_ruangan' => $item['nama_ruangan'],
                ],
                $item
            );
        }
    }
}

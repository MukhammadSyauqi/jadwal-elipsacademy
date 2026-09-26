<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangList = [
            [
                'id' => 1,
                'nama_cabang' => 'Buduran',
                'alamat' => 'Jl. Raya Buduran, Sidoarjo',
                'status' => 'aktif',
            ],
            [
                'id' => 2,
                'nama_cabang' => 'Candi',
                'alamat' => 'Jl. Raya Candi, Sidoarjo',
                'status' => 'aktif',
            ],
            [
                'id' => 3,
                'nama_cabang' => 'Gubeng',
                'alamat' => 'Jl. Raya Gubeng No. 45, Surabaya',
                'status' => 'aktif',
            ],
        ];

        foreach ($cabangList as $item) {
            Cabang::updateOrCreate(
                ['id' => $item['id']],
                $item
            );
        }
    }
}

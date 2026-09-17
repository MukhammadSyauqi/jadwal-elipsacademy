<?php

namespace Database\Seeders;

use App\Models\Tentor;
use Illuminate\Database\Seeder;

class TentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tentors = [
            [
                'id' => 1,
                'nama' => 'Budi',
                'no_hp' => '081234567890',
                'keahlian' => 'Microsoft Office',
                'status' => 'aktif',
            ],
            [
                'id' => 2,
                'nama' => 'Andi',
                'no_hp' => '081234567891',
                'keahlian' => 'Programming',
                'status' => 'aktif',
            ],
            [
                'id' => 3,
                'nama' => 'Rina',
                'no_hp' => '081234567892',
                'keahlian' => 'Graphic Design',
                'status' => 'aktif',
            ],
        ];

        foreach ($tentors as $item) {
            Tentor::updateOrCreate(
                ['id' => $item['id']],
                $item
            );
        }
    }
}

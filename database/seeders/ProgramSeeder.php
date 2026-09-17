<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'id' => 1,
                'nama_program' => 'Microsoft Office',
                'kategori' => 'Office',
                'status' => 'aktif',
            ],
            [
                'id' => 2,
                'nama_program' => 'Web Programming',
                'kategori' => 'Programming',
                'status' => 'aktif',
            ],
            [
                'id' => 3,
                'nama_program' => 'Graphic Design',
                'kategori' => 'Design',
                'status' => 'aktif',
            ],
        ];

        foreach ($programs as $item) {
            Program::updateOrCreate(
                ['id' => $item['id']],
                $item
            );
        }
    }
}

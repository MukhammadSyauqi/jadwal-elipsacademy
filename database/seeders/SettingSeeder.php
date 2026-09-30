<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'wa_template'],
            [
                'value' => "Selamat {sesi} kak\nIzin Mengingatkan kak, besok hari {hari} Tanggal {tanggal} ada kelas {program} pada pukul {jam} di Elips Academy {cabang}. Terimakasih🙏",
                'description' => 'Template pesan WhatsApp reminder ke tentor',
            ]
        );
    }
}

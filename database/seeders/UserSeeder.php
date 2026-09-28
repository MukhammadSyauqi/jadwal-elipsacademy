<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cleanup old legacy email if exists
        User::where('email', 'gubeng@elipsacademy.com')->delete();

        $users = [
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@elipsacademy.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'cabang_id' => null,
            ],
            [
                'nama' => 'Admin Candi',
                'email' => 'admin.candi@elipsacademy.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'cabang_id' => 2,
            ],
            [
                'nama' => 'Admin Gubeng',
                'email' => 'admin.gubeng@elipsacademy.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'cabang_id' => 3,
            ],
            [
                'nama' => 'Admin Buduran',
                'email' => 'admin.buduran@elipsacademy.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'cabang_id' => 1,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

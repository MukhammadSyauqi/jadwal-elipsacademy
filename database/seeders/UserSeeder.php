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
        $users = [
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@elipsacademy.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ],
            [
                'nama' => 'Admin Candi',
                'email' => 'admin.candi@elipsacademy.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'nama' => 'Staff Gubeng',
                'email' => 'gubeng@elipsacademy.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'nama' => 'Admin Buduran',
                'email' => 'admin.buduran@elipsacademy.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
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

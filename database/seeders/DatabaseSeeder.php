<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Buat akun default untuk development.
     *
     * ⚠️  GANTI PASSWORD sebelum deploy ke production!
     *     Jalankan: php artisan db:seed
     */
    public function run(): void
    {
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@webwika.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('Admin@12345'),
                'role'     => 'admin',
            ]
        );

        // Akun User biasa
        User::updateOrCreate(
            ['email' => 'user@webwika.com'],
            [
                'name'     => 'User Operator',
                'password' => Hash::make('User@12345'),
                'role'     => 'user',
            ]
        );
    }
}
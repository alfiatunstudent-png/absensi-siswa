<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin default.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@absensi.test'],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
            ],
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Rifujin na Magonote',
            'email' => 'madanishofa@gmail.com',
            'profile_picture' => 'profile_682be39fb16a5.webp',
            'password' => 'qwerty',
            'role' => 'borrower',
        ]);
    }
}

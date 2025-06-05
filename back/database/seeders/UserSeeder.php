<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Jānis Bērziņš', 'email' => 'janis@janis'],
            ['name' => 'Elīna Bērziņa', 'email' => 'elina@elina'],
            ['name' => 'Kārlīne Liepiņa', 'email' => 'karlina@karlina'],
            ['name' => 'Diāna Strautiņa', 'email' => 'diana@diana'],
            ['name' => 'Pēteris Liepiņš', 'email' => 'peteris@peteris'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'), // 🔐 All users use 'password'
            ]);
        }
    }
}

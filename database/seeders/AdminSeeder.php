<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'admin',
        ]);

        Admin::create([
            'user_id' => $user->id,
            'nip' => '123456789',
            'nama' => 'Admin SD',
            'jk' => 'L',
        ]);
    }
}

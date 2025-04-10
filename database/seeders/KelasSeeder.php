<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = ["6A", "6B", "6C", "6D", "6E"];

        foreach ($kelas as $value) {
            Kelas::create([
                'nama' => $value,
            ]);
        }
    }
}

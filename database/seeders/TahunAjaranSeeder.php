<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = ["2019/2020", "2020/2021", "2021/2022", "2022/2023", "2023/2024", "2024/2025"];

        foreach ($kelas as $value) {
            TahunAjaran::create([
                'tahun' => $value,
                'status' => 'aktif',
            ]);
        }
    }
}

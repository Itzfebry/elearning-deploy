<?php

namespace App\Repositories;

use App\Models\User;

class DashboardRepository
{

    public function getData()
    {
        $data = [];
        $admin = User::where('role', "admin")->count();
        $guru = User::where('role', "guru")->count();
        $siswa = User::where('role', "siswa")->count();
        $data['admin'] = $admin;
        $data['guru'] = $guru;
        $data['siswa'] = $siswa;

        return $data;
    }

}
<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use App\Models\Siswa;

class DashboardRepository
{
    public function getData($tahunAjaran = null, $kelas = null)
    {
        $data = [];
        
        // Get active academic year if not specified
        if (!$tahunAjaran) {
            $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
            if ($tahunAjaran) {
                $tahunAjaran = $tahunAjaran->tahun;
            }
        }

        // Get all academic years for dropdown
        $data['tahun_ajaran_list'] = TahunAjaran::select('tahun')->get()->pluck('tahun');
        // Get all classes for dropdown
        $data['kelas_list'] = Kelas::select('nama')->get()->pluck('nama');
        
        // User statistics (not affected by academic year or class)
        $data['admin'] = User::where('role', "admin")->count();
        $data['guru'] = User::where('role', "guru")->count();
        
        // Academic statistics with year and class filter
        $siswaQuery = Siswa::query();
        $mataPelajaranQuery = MataPelajaran::query();
        $waliKelasQuery = WaliKelas::query();

        if ($tahunAjaran && $tahunAjaran !== 'all') {
            $siswaQuery->where('tahun_ajaran', $tahunAjaran);
            $mataPelajaranQuery->where('tahun_ajaran', $tahunAjaran);
            $waliKelasQuery->where('tahun_ajaran', $tahunAjaran);
        }

        if ($kelas && $kelas !== 'all') {
            $siswaQuery->where('kelas', $kelas);
            $mataPelajaranQuery->where('kelas', $kelas);
            $waliKelasQuery->where('kelas', $kelas);
        }

        $data['siswa'] = $siswaQuery->count();
        // Kelas count needs special handling as it's not directly tied to siswa by year/class but to the existing classes
        // If filtering by class, we only count that specific class, otherwise all classes
        $data['kelas'] = ($kelas && $kelas !== 'all') ? 1 : Kelas::count();
        $data['mata_pelajaran'] = $mataPelajaranQuery->count();
        $data['wali_kelas'] = $waliKelasQuery->count();

        // Get active academic year info
        $activeYear = TahunAjaran::where('status', 'aktif')->first();
        if ($activeYear) {
            $data['tahun_ajaran_aktif'] = $activeYear->tahun;
        } else {
            $data['tahun_ajaran_aktif'] = 'Belum ada tahun ajaran aktif';
        }

        // Store selected year and class
        $data['selected_year'] = $tahunAjaran;
        $data['selected_kelas'] = $kelas;

        return $data;
    }
}
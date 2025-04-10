<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    protected $table = "riwayat_kelas";

    protected $fillable = [
        "kelas",
        "tahun_ajaran",
        "wali_nip",
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas', 'nama');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'wali_nip', 'nip');
    }
}

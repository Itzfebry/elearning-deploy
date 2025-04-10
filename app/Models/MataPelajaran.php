<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = "matapelajaran";

    protected $fillable = [
        "nama",
        "guru_nip",
        "kelas",
        "tahun_ajaran",
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, "guru_nip", "nip");
    }
    public function kelas()
    {
        return $this->belongsTo(Guru::class, "kelas", "nama");
    }
    public function tahunAjaran()
    {
        return $this->belongsTo(Guru::class, "tahun_ajaran", "tahun");
    }
}

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

    public function materi()
    {
        return $this->hasMany(Materi::class, "matapelajaran_id", "id");
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, "guru_nip", "nip");
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, "kelas", "nama");
    }
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, "tahun_ajaran", "tahun");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = "materi";

    protected $fillable = [
        "tanggal",
        "matapelajaran_id",
        "semester",
        "type",
        "judul_materi",
        "deskripsi",
        "path",
        "tahun_ajaran"
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, "matapelajaran_id", "id");
    }
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, "tahun_ajaran", "tahun");
    }
}

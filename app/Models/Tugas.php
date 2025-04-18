<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = "tugas";

    protected $fillable = [
        "tanggal",
        "tenggat",
        "guru_nip",
        "nama",
        "matapelajaran_id",
        "kelas",
        "tahun_ajaran",
    ];

    public function submitTugas()
    {
        return $this->hasOne(SubmitTugas::class, "tugas_id", "id");
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, "guru_nip", "nip");
    }
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, "matapelajaran_id", "id");
    }
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, "tahun_ajaran", "id");
    }
}

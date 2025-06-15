<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmitTugas extends Model
{
    protected $table = "submit_tugas";

    protected $fillable = [
        "id",
        "tanggal",
        "nisn",
        "tugas_id",
        "text",
        "file",
        "nilai"
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, "nisn", "nisn");
    }
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, "tugas_id", "id");
    }
}

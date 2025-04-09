<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = "siswa";

    protected $fillable = [
        "user_id",
        "nisn",
        "nama",
        "jk",
        "kelas",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, "kelas", "nama");
    }
}

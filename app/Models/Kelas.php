<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = "kelas";
    protected $fillable = [
        "nama",
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas', 'nama');
    }

    // public function guru()
    // {
    //     return $this->belongsTo(Guru::class, 'nip_wali', 'nip');
    // }
}

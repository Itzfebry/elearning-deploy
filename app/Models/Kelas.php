<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = "kelas";
    protected $fillable = [
        "id",
        "nama",
        "nip_wali",
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'nip_wali', 'nip');
    }
}

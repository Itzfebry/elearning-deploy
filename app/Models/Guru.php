<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = [
        'id',
        'user_id',
        'nip',
        'nama',
        'jk',
    ];

    // public function kelas()
    // {
    //     return $this->hasOne(Kelas::class, 'nip_wali', 'nip');
    // }

    public function guru()
    {
        return $this->hasOne(Guru::class, 'wali_nip', 'nip');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

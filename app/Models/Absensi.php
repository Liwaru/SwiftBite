<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_user',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
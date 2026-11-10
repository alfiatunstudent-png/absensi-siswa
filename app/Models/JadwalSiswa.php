<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSiswa extends Model
{
    use HasFactory;

    protected $table = 'jadwal_siswa';

    protected $primaryKey = 'id_jadwal_siswa';

    public $timestamps = false;

    protected $fillable = [
        'id_mapel_siswa',
        'id_jadwal',
    ];

    protected $casts = [
        'id_jadwal_siswa' => 'integer',
        'id_mapel_siswa' => 'integer',
        'id_jadwal' => 'integer',
    ];
}

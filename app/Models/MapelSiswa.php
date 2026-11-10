<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapelSiswa extends Model
{
    use HasFactory;

    protected $table = 'mapel_siswa';

    protected $primaryKey = 'id_mapel_siswa';

    public $timestamps = false;

    protected $fillable = [
        'nisn',
        'kode_mapel',
        'kode_kelas',
    ];

    protected $casts = [
        'id_mapel_siswa' => 'integer',
    ];
}

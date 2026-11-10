<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'mapel_id',
    'guru_id',
    'kelas',
    'hari',
    'jam_mulai',
    'jam_selesai',
])]
class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    /**
     * Daftar hari sekolah yang tersedia.
     *
     * @var array<int, string>
     */
    public const HARI = [
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu',
    ];

    /**
     * Urutkan jadwal sesuai urutan hari lalu jam mulai.
     */
    public function scopeUrut(Builder $query): Builder
    {
        return $query
            ->orderByRaw(
                "case jadwal.hari
                    when 'Senin' then 1
                    when 'Selasa' then 2
                    when 'Rabu' then 3
                    when 'Kamis' then 4
                    when 'Jumat' then 5
                    when 'Sabtu' then 6
                    else 7 end"
            )
            ->orderBy('jadwal.jam_mulai');
    }

    /**
     * Join mapel dan guru dengan kolom alias.
     */
    public function scopeLengkap(Builder $query): Builder
    {
        return $query
            ->join('mapel', 'mapel.id', '=', 'jadwal.mapel_id')
            ->join('guru', 'guru.id', '=', 'jadwal.guru_id')
            ->select(
                'jadwal.*',
                'mapel.kode as mapel_kode',
                'mapel.nama as mapel_nama',
                'guru.nip as guru_nip',
                'guru.nama as guru_nama'
            );
    }

    /**
     * Nama hari ini dalam bahasa Indonesia.
     */
    public static function hariIni(): string
    {
        $map = [
            'Minggu', 'Senin', 'Selasa', 'Rabu',
            'Kamis', 'Jumat', 'Sabtu',
        ];

        return $map[(int) now()->format('w')];
    }

    public function jam(): string
    {
        return substr($this->jam_mulai, 0, 5)
            .' - '
            .substr($this->jam_selesai, 0, 5);
    }

    public function badgeHari(): string
    {
        return match ($this->hari) {
            'Senin', 'Selasa' => 'primary',
            'Rabu', 'Kamis' => 'success',
            'Jumat' => 'warning',
            default => 'secondary',
        };
    }
}

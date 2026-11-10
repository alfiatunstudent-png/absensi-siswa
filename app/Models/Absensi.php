<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

#[Fillable([
    'jadwal_id',
    'siswa_id',
    'tanggal',
    'status',
    'keterangan',
])]
class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    /**
     * Daftar status absensi yang tersedia.
     *
     * @var array<string, string>
     */
    public const STATUS = [
        'hadir' => 'Hadir',
        'sakit' => 'Sakit',
        'izin' => 'Izin',
        'alfa' => 'Alfa',
    ];

    /**
     * Tanggal absensi disimpan sebagai Y-m-d.
     */
    protected function tanggal(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value === null
                ? null
                : Carbon::parse($value),
            set: fn ($value) => $value === null
                ? null
                : Carbon::parse($value)->toDateString(),
        );
    }

    /**
     * Join jadwal, mapel, guru, dan siswa.
     */
    public function scopeLengkap(Builder $query): Builder
    {
        return $query
            ->join('jadwal', 'jadwal.id', '=', 'absensi.jadwal_id')
            ->join('mapel', 'mapel.id', '=', 'jadwal.mapel_id')
            ->join('guru', 'guru.id', '=', 'jadwal.guru_id')
            ->join('siswa', 'siswa.id', '=', 'absensi.siswa_id')
            ->select(
                'absensi.*',
                'jadwal.kelas as jadwal_kelas',
                'jadwal.hari as jadwal_hari',
                'jadwal.jam_mulai as jadwal_jam_mulai',
                'jadwal.jam_selesai as jadwal_jam_selesai',
                'mapel.nama as mapel_nama',
                'guru.nama as guru_nama',
                'siswa.nama as siswa_nama',
                'siswa.nisn as siswa_nisn'
            );
    }

    public function jadwalJam(): string
    {
        return substr((string) $this->jadwal_jam_mulai, 0, 5)
            .' - '
            .substr((string) $this->jadwal_jam_selesai, 0, 5);
    }

    public function statusLabel(): string
    {
        return self::STATUS[$this->status] ?? ucfirst($this->status);
    }

    public function badgeStatus(): string
    {
        return match ($this->status) {
            'hadir' => 'success',
            'sakit' => 'warning',
            'izin' => 'info',
            'alfa' => 'danger',
            default => 'secondary',
        };
    }
}

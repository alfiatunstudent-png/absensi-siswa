<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

#[Fillable([
    'user_id',
    'nisn',
    'nama',
    'kelas',
    'jenis_kelamin',
    'tanggal_lahir',
    'alamat',
])]
class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    /**
     * Tanggal lahir disimpan sebagai Y-m-d, dibaca sebagai Carbon.
     */
    protected function tanggalLahir(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => isset($attributes['tanggal_lahir'])
                    ? Carbon::parse($attributes['tanggal_lahir'])
                    : null,
            set: fn ($value) => $value === null
                ? null
                : Carbon::parse($value)->toDateString(),
        );
    }

    /**
     * Email akun login milik siswa.
     */
    public function emailAkun(): ?string
    {
        return User::where('id', $this->user_id)->value('email');
    }

    /**
     * Rekap jumlah absensi per status beserta persentase kehadiran.
     *
     * @return array<string, int>
     */
    public function rekapAbsensi(): array
    {
        $counts = Absensi::where('siswa_id', $this->id)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $rekap = [];

        foreach (array_keys(Absensi::STATUS) as $status) {
            $rekap[$status] = (int) ($counts[$status] ?? 0);
        }

        $rekap['total'] = array_sum($rekap);
        $rekap['persentase'] = $rekap['total'] > 0
            ? (int) round(
                ($rekap['hadir'] ?? 0) / $rekap['total'] * 100
            )
            : 0;

        return $rekap;
    }

    public function jenisKelaminLabel(): string
    {
        return $this->jenis_kelamin === 'L'
            ? 'Laki-laki'
            : 'Perempuan';
    }

    public function absensiTerbaru(int $limit): \Illuminate\Database\Eloquent\Collection
    {
        return Absensi::lengkap()
            ->where('absensi.siswa_id', $this->id)
            ->orderByDesc('absensi.tanggal')
            ->limit($limit)
            ->get();
    }

    /**
     * Daftar kelas unik yang digunakan siswa.
     */
    public function kelasList(): Collection
    {
        return self::query()
            ->whereNotNull('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');
    }
}

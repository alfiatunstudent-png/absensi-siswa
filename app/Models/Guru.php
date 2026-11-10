<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'nip', 'nama', 'no_telepon'])]
class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    /**
     * Email akun login milik guru.
     */
    public function emailAkun(): ?string
    {
        return User::where('id', $this->user_id)->value('email');
    }

    /**
     * Daftar kelas unik yang diajar guru.
     *
     * @return array<int, string>
     */
    public function kelasDiampu(): array
    {
        return Jadwal::where('guru_id', $this->id)
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas')
            ->all();
    }
}

<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    /**
     * Riwayat absensi siswa beserta rekap kehadiran.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'status' => ['nullable', 'string'],
        ]);

        $siswa = $request->user()->profilSiswa();

        abort_if(
            $siswa === null,
            403,
            'Akun ini belum terhubung dengan data siswa.'
        );

        $absensis = Absensi::query()
            ->lengkap()
            ->where('absensi.siswa_id', $siswa->id)
            ->when(
                $request->filled('status') && array_key_exists(
                    $request->input('status'),
                    Absensi::STATUS
                ),
                fn ($query) => $query->where(
                    'absensi.status',
                    $request->input('status')
                ),
            )
            ->orderByDesc('absensi.tanggal')
            ->orderByDesc('absensi.id')
            ->paginate(15)
            ->withQueryString();

        $rekap = $siswa->rekapAbsensi();

        return view('siswa.absensi.index', compact(
            'siswa',
            'absensis',
            'rekap'
        ));
    }
}

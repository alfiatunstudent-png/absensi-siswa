<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapelController extends Controller
{
    /**
     * Mata pelajaran kelas siswa beserta guru pengampu.
     */
    public function index(Request $request): View
    {
        $siswa = $request->user()->profilSiswa();

        abort_if(
            $siswa === null,
            403,
            'Akun ini belum terhubung dengan data siswa.'
        );

        $jadwals = Jadwal::query()
            ->lengkap()
            ->where('jadwal.kelas', $siswa->kelas)
            ->urut()
            ->get()
            ->groupBy('mapel_id');

        return view('siswa.mapel.index', compact(
            'siswa',
            'jadwals'
        ));
    }
}

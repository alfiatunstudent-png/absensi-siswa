<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    /**
     * Jadwal pelajaran kelas siswa, dikelompokkan per hari.
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
            ->groupBy('hari');

        return view('siswa.jadwal.index', compact('siswa', 'jadwals'));
    }
}

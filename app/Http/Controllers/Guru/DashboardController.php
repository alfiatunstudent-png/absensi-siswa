<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard guru dengan jadwal hari ini dan ringkasan.
     */
    public function index(Request $request): View
    {
        $guru = $request->user()->profilGuru();

        abort_if(
            $guru === null,
            403,
            'Akun ini belum terhubung dengan data guru.'
        );

        $jadwalHariIni = Jadwal::query()
            ->lengkap()
            ->where('jadwal.guru_id', $guru->id)
            ->where('jadwal.hari', Jadwal::hariIni())
            ->urut()
            ->get();

        $stats = [
            'mapel' => Mapel::query()
                ->join('jadwal', 'jadwal.mapel_id', '=', 'mapel.id')
                ->where('jadwal.guru_id', $guru->id)
                ->distinct()
                ->count('mapel.id'),

            'jadwal' => Jadwal::where('guru_id', $guru->id)->count(),

            'siswa' => Siswa::whereIn('kelas', $guru->kelasDiampu())->count(),

            'absensi_hari_ini' => Absensi::query()
                ->join('jadwal', 'jadwal.id', '=', 'absensi.jadwal_id')
                ->where('jadwal.guru_id', $guru->id)
                ->whereDate('absensi.tanggal', today())
                ->count(),
        ];

        // TAMBAHAN LATIHAN 1: Menghitung jumlah siswa per kelas.
        $siswaPerKelas = [];

        foreach ($guru->kelasDiampu() as $kelas) {
            $siswaPerKelas[$kelas] = Siswa::where('kelas', $kelas)->count();
        }

        return view('guru.dashboard', compact(
            'guru',
            'jadwalHariIni',
            'stats',
            'siswaPerKelas' // TAMBAHAN LATIHAN 1
        ));
    }
}

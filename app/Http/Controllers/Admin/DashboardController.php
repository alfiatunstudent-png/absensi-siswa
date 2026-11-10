<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard admin dengan ringkasan data.
     */
    public function index(): View
    {
        $stats = [
            'guru' => Guru::count(),
            'siswa' => Siswa::count(),
            'mapel' => Mapel::count(),
            'jadwal' => Jadwal::count(),
            'absensi' => Absensi::count(),
            'hadir_hari_ini' => Absensi::whereDate('tanggal', today())
                ->where('status', 'hadir')
                ->count(),
        ];

        $jadwalHariIni = Jadwal::query()
            ->lengkap()
            ->where('jadwal.hari', Jadwal::hariIni())
            ->urut()
            ->get();

        $absensiTerbaru = Absensi::query()
            ->lengkap()
            ->orderByDesc('absensi.tanggal')
            ->orderByDesc('absensi.id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'jadwalHariIni',
            'absensiTerbaru'
        ));
    }
}

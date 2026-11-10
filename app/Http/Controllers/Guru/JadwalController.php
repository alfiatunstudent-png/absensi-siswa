<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    /**
     * Jadwal mengajar guru, dikelompokkan per hari.
     */
    public function index(Request $request): View
    {
        $guru = $request->user()->profilGuru();

        abort_if(
            $guru === null,
            403,
            'Akun ini belum terhubung dengan data guru.'
        );

        $jadwals = Jadwal::query()
            ->lengkap()
            ->where('jadwal.guru_id', $guru->id)
            ->urut()
            ->get()
            ->groupBy('hari');

        return view('guru.jadwal.index', compact('guru', 'jadwals'));
    }
}

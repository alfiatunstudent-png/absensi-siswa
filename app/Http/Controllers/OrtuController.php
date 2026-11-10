<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrtuController extends Controller
{
    /**
     * Halaman pencarian data absensi siswa berdasarkan NISN untuk orang tua.
     */
    public function index(Request $request): View
    {
        $siswa = null;
        $absensis = null;
        $rekap = null;

        if ($request->filled('nisn')) {
            $request->validate([
                'nisn' => ['string', 'max:20'],
            ]);

            $siswa = Siswa::where(
                'nisn',
                $request->string('nisn')->trim()->toString()
            )->first();

            if ($siswa === null) {
                session()->flash(
                    'error',
                    'Data siswa dengan NISN tersebut tidak ditemukan.'
                );
            } else {
                $absensis = Absensi::query()
                    ->lengkap()
                    ->where('absensi.siswa_id', $siswa->id)
                    ->orderByDesc('absensi.tanggal')
                    ->orderByDesc('absensi.id')
                    ->paginate(15)
                    ->withQueryString();

                $rekap = $siswa->rekapAbsensi();
            }
        }

        return view('ortu.index', compact(
            'siswa',
            'absensis',
            'rekap'
        ));
    }
}

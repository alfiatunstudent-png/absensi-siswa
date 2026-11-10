<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapelController extends Controller
{
    /**
     * Mata pelajaran yang diampu guru.
     */
    public function index(Request $request): View
    {
        $guru = $request->user()->profilGuru();

        abort_if($guru === null, 403, 'Akun ini belum terhubung dengan data guru.');

        $mapels = Mapel::query()
            ->join('jadwal', 'jadwal.mapel_id', '=', 'mapel.id')
            ->where('jadwal.guru_id', $guru->id)
            ->select('mapel.id', 'mapel.kode', 'mapel.nama')
            ->selectRaw('count(distinct jadwal.kelas) as jumlah_kelas')
            ->groupBy('mapel.id', 'mapel.kode', 'mapel.nama')
            ->orderBy('mapel.nama')
            ->get();

        return view('guru.mapel.index', compact('guru', 'mapels'));
    }
}

<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /**
     * Daftar siswa dari kelas yang diajar guru.
     */
    public function index(Request $request): View
    {
        $guru = $request->user()->profilGuru();

        abort_if($guru === null, 403, 'Akun ini belum terhubung dengan data guru.');

        $kelasList = $guru->kelasDiampu();

        $siswas = Siswa::query()
            ->whereIn('kelas', $kelasList)
            ->when($request->filled('kelas'), fn ($query) => $query->where('kelas', $request->input('kelas')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $kataKunci = '%'.$request->input('q').'%';

                $query->where(function ($query) use ($kataKunci) {
                    $query->where('nama', 'like', $kataKunci)
                        ->orWhere('nisn', 'like', $kataKunci);
                });
            })
            ->orderBy('kelas')
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('guru.siswa.index', compact('guru', 'siswas', 'kelasList'));
    }
}

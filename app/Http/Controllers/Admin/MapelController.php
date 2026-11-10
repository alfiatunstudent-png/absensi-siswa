<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mapel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MapelController extends Controller
{
    /**
     * Daftar mata pelajaran.
     */
    public function index(Request $request): View
    {
        $mapels = Mapel::query()
            ->leftJoin('jadwal', 'jadwal.mapel_id', '=', 'mapel.id')
            ->select(
                'mapel.id',
                'mapel.kode',
                'mapel.nama',
                'mapel.created_at',
                'mapel.updated_at'
            )
            ->selectRaw('count(jadwal.id) as jadwal_count')
            ->when($request->filled('q'), function ($query) use ($request) {
                $kataKunci = '%'.$request->input('q').'%';

                $query->where(function ($query) use ($kataKunci) {
                    $query->where('mapel.kode', 'like', $kataKunci)
                        ->orWhere('mapel.nama', 'like', $kataKunci);
                });
            })
            ->groupBy(
                'mapel.id',
                'mapel.kode',
                'mapel.nama',
                'mapel.created_at',
                'mapel.updated_at'
            )
            ->orderBy('mapel.nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.mapel.index', compact('mapels'));
    }

    /**
     * Form tambah mata pelajaran.
     */
    public function create(): View
    {
        return view('admin.mapel.form', ['mapel' => null]);
    }

    /**
     * Simpan mata pelajaran baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mapel,kode'],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        Mapel::create($data);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Form ubah mata pelajaran.
     */
    public function edit(Mapel $mapel): View
    {
        return view('admin.mapel.form', compact('mapel'));
    }

    /**
     * Perbarui mata pelajaran.
     */
    public function update(Request $request, Mapel $mapel): RedirectResponse
    {
        $data = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mapel', 'kode')->ignore($mapel->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $mapel->update($data);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Hapus mata pelajaran.
     */
    public function destroy(Mapel $mapel): RedirectResponse
    {
        if (Jadwal::where('mapel_id', $mapel->id)->exists()) {
            return back()->with(
                'error',
                'Mata pelajaran tidak bisa dihapus karena masih dipakai pada jadwal.'
            );
        }

        $mapel->delete();

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}

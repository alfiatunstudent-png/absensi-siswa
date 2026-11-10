<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    /**
     * Daftar absensi dengan filter jadwal, tanggal, dan status.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'jadwal_id' => ['nullable', 'integer'],
            'tanggal' => ['nullable', 'date'],
            'status' => ['nullable', 'string'],
        ]);

        $absensis = Absensi::query()
            ->lengkap()
            ->when(
                $request->filled('jadwal_id'),
                fn ($query) => $query->where('absensi.jadwal_id', $request->integer('jadwal_id'))
            )
            ->when(
                $request->filled('tanggal'),
                fn ($query) => $query->whereDate('absensi.tanggal', $request->date('tanggal'))
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('absensi.status', $request->input('status'))
            )
            ->orderByDesc('absensi.tanggal')
            ->orderByDesc('absensi.id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.absensi.index', [
            'absensis' => $absensis,
            'jadwals' => $this->jadwalList(),
        ]);
    }

    /**
     * Form input absensi per jadwal dan tanggal.
     */
    public function create(Request $request): View
    {
        $request->validate([
            'jadwal_id' => ['nullable', 'integer'],
            'tanggal' => ['nullable', 'date'],
        ]);

        $tanggal = ($request->filled('tanggal') ? $request->date('tanggal') : today())
            ->toDateString();

        $selectedJadwal = null;
        $siswas = collect();
        $absensiAda = collect();
        $absensiKemarin = collect();

        if ($request->filled('jadwal_id')) {
            $selectedJadwal = Jadwal::query()
                ->lengkap()
                ->where('jadwal.id', $request->integer('jadwal_id'))
                ->first();

            if ($selectedJadwal !== null) {
                $siswas = Siswa::where('kelas', $selectedJadwal->kelas)
                    ->orderBy('nama')
                    ->get();

                $absensiAda = Absensi::where('jadwal_id', $selectedJadwal->id)
                    ->whereDate('tanggal', $tanggal)
                    ->get()
                    ->keyBy('siswa_id');

                // Mengambil absensi satu hari sebelumnya untuk jadwal yang sama.
                $tanggalKemarin = Carbon::parse($tanggal)
                    ->subDay()
                    ->toDateString();

                $absensiKemarin = Absensi::where('jadwal_id', $selectedJadwal->id)
                    ->whereDate('tanggal', $tanggalKemarin)
                    ->get()
                    ->keyBy('siswa_id');
            }
        }

        return view('admin.absensi.create', [
            'jadwals' => $this->jadwalList(),
            'selectedJadwal' => $selectedJadwal,
            'siswas' => $siswas,
            'absensiAda' => $absensiAda,
            'absensiKemarin' => $absensiKemarin,
            'tanggal' => $tanggal,
        ]);
    }

    /**
     * Simpan absensi satu jadwal untuk satu tanggal.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'jadwal_id' => ['required', 'exists:jadwal,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'array', 'min:1'],
            'status.*' => ['required', Rule::in(array_keys(Absensi::STATUS))],
            'keterangan' => ['nullable', 'array'],
            'keterangan.*' => ['nullable', 'string', 'max:255'],
        ]);

        $jadwal = Jadwal::findOrFail($data['jadwal_id']);

        $siswaIds = Siswa::where('kelas', $jadwal->kelas)
            ->whereIn('id', array_keys($data['status']))
            ->pluck('id');

        foreach ($siswaIds as $siswaId) {
            Absensi::updateOrCreate(
                [
                    'jadwal_id' => $jadwal->id,
                    'siswa_id' => $siswaId,
                    'tanggal' => $data['tanggal'],
                ],
                [
                    'status' => $data['status'][$siswaId],
                    'keterangan' => $data['keterangan'][$siswaId] ?? null,
                ],
            );
        }

        return redirect()
            ->route('admin.absensi.index', [
                'jadwal_id' => $jadwal->id,
                'tanggal' => $data['tanggal'],
            ])
            ->with('success', 'Absensi berhasil disimpan.');
    }

    /**
     * Form ubah satu catatan absensi.
     */
    public function edit(Absensi $absensi): View
    {
        $absensi = Absensi::query()
            ->lengkap()
            ->where('absensi.id', $absensi->id)
            ->firstOrFail();

        return view('admin.absensi.edit', compact('absensi'));
    }

    /**
     * Perbarui satu catatan absensi.
     */
    public function update(Request $request, Absensi $absensi): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Absensi::STATUS))],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $absensi->update($data);

        return redirect()
            ->route('admin.absensi.index', [
                'jadwal_id' => $absensi->jadwal_id,
                'tanggal' => $absensi->tanggal->toDateString(),
            ])
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Hapus satu catatan absensi.
     */
    public function destroy(Absensi $absensi): RedirectResponse
    {
        $absensi->delete();

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', 'Absensi berhasil dihapus.');
    }

    /**
     * Daftar jadwal untuk dropdown.
     *
     * @return Collection<int, Jadwal>
     */
    private function jadwalList(): Collection
    {
        return Jadwal::query()
            ->lengkap()
            ->orderBy('jadwal.kelas')
            ->urut()
            ->get();
    }
}

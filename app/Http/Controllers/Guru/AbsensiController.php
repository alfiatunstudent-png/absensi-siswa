<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    /**
     * Daftar absensi yang pernah diinput guru, dengan filter.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'jadwal_id' => ['nullable', 'integer'],
            'tanggal' => ['nullable', 'date'],
            'status' => ['nullable', 'string'],
            'urut' => ['nullable', Rule::in(['terbaru', 'terlama'])],
        ]);

        $guru = $request->user()->profilGuru();

        abort_if($guru === null, 403, 'Akun ini belum terhubung dengan data guru.');

        // Menentukan urutan absensi.
        $urut = $request->input('urut', 'terbaru');

        $absensis = Absensi::query()
            ->lengkap()
            ->where('jadwal.guru_id', $guru->id)
            ->when($request->filled('jadwal_id'), fn ($query) => $query->where('absensi.jadwal_id', $request->integer('jadwal_id')))
            ->when($request->filled('tanggal'), fn ($query) => $query->whereDate('absensi.tanggal', $request->date('tanggal')))
            ->when($request->filled('status'), fn ($query) => $query->where('absensi.status', $request->input('status')))
            ->when(
                $urut === 'terlama',
                fn ($query) => $query->orderBy('absensi.tanggal'),
                fn ($query) => $query->orderByDesc('absensi.tanggal')
            )
            ->orderByDesc('absensi.id')
            ->paginate(20)
            ->withQueryString();

        return view('guru.absensi.index', [
            'absensis' => $absensis,
            'jadwals' => $this->jadwalList($guru),
            'urut' => $urut,
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

        $guru = $request->user()->profilGuru();

        abort_if($guru === null, 403, 'Akun ini belum terhubung dengan data guru.');

        $tanggal = ($request->filled('tanggal') ? $request->date('tanggal') : today())->toDateString();

        $selectedJadwal = null;
        $siswas = collect();
        $absensiAda = collect();
        $absensiKemarin = collect();

        if ($request->filled('jadwal_id')) {
            $selectedJadwal = Jadwal::query()
                ->lengkap()
                ->where('jadwal.id', $request->integer('jadwal_id'))
                ->where('jadwal.guru_id', $guru->id)
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

        return view('guru.absensi.create', [
            'jadwals' => $this->jadwalList($guru),
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
        $guru = $request->user()->profilGuru();

        abort_if($guru === null, 403, 'Akun ini belum terhubung dengan data guru.');

        $data = $request->validate([
            'jadwal_id' => ['required', 'exists:jadwal,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'array', 'min:1'],
            'status.*' => ['required', Rule::in(array_keys(Absensi::STATUS))],
            'keterangan' => ['nullable', 'array'],
            'keterangan.*' => ['nullable', 'string', 'max:255'],
        ]);

        // TAMBAHAN LATIHAN 3:
        // Keterangan wajib diisi jika status bukan Hadir.
        foreach ($data['status'] as $siswaId => $status) {
            if (
                $status !== 'hadir' &&
                blank($data['keterangan'][$siswaId] ?? null)
            ) {
                throw ValidationException::withMessages([
                    "keterangan.{$siswaId}" => 'Keterangan wajib diisi jika status bukan Hadir.',
                ]);
            }
        }

        $jadwal = Jadwal::where('id', $data['jadwal_id'])
            ->where('guru_id', $guru->id)
            ->firstOrFail();

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
            ->route('guru.absensi.index', [
                'jadwal_id' => $jadwal->id,
                'tanggal' => $data['tanggal'],
            ])
            ->with('success', 'Absensi berhasil disimpan.');
    }

    /**
     * Form ubah satu catatan absensi milik guru.
     */
    public function edit(Request $request, Absensi $absensi): View
    {
        $this->pastikanMilikGuru($request, $absensi);

        $absensi = Absensi::query()
            ->lengkap()
            ->where('absensi.id', $absensi->id)
            ->firstOrFail();

        return view('guru.absensi.edit', compact('absensi'));
    }

    /**
     * Perbarui satu catatan absensi milik guru.
     */
    public function update(Request $request, Absensi $absensi): RedirectResponse
    {
        $this->pastikanMilikGuru($request, $absensi);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Absensi::STATUS))],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        // TAMBAHAN LATIHAN 3:
        // Keterangan wajib diisi jika status bukan Hadir.
        if (
            $data['status'] !== 'hadir' &&
            blank($data['keterangan'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'keterangan' => 'Keterangan wajib diisi jika status bukan Hadir.',
            ]);
        }

        $absensi->update($data);

        return redirect()
            ->route('guru.absensi.index', [
                'jadwal_id' => $absensi->jadwal_id,
                'tanggal' => $absensi->tanggal->toDateString(),
            ])
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Hapus satu catatan absensi milik guru.
     */
    public function destroy(Request $request, Absensi $absensi): RedirectResponse
    {
        $this->pastikanMilikGuru($request, $absensi);

        $absensi->delete();

        return redirect()
            ->route('guru.absensi.index')
            ->with('success', 'Absensi berhasil dihapus.');
    }

    /**
     * Pastikan catatan absensi berasal dari jadwal milik guru yang login.
     */
    private function pastikanMilikGuru(Request $request, Absensi $absensi): void
    {
        $guru = $request->user()->profilGuru();

        abort_if($guru === null, 403, 'Akun ini belum terhubung dengan data guru.');

        $pemilik = Jadwal::where('id', $absensi->jadwal_id)->value('guru_id');

        abort_if($pemilik !== $guru->id, 403, 'Anda tidak berhak mengubah absensi ini.');
    }

    /**
     * Daftar jadwal milik guru untuk dropdown.
     *
     * @return Collection<int, Jadwal>
     */
    private function jadwalList(Guru $guru): Collection
    {
        return Jadwal::query()
            ->lengkap()
            ->where('jadwal.guru_id', $guru->id)
            ->orderBy('jadwal.kelas')
            ->urut()
            ->get();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /**
     * Daftar siswa.
     */
    public function index(Request $request): View
    {
        $siswas = Siswa::query()
            ->leftJoin('users', 'users.id', '=', 'siswa.user_id')
            ->leftJoin('absensi', 'absensi.siswa_id', '=', 'siswa.id')
            ->select(
                'siswa.id',
                'siswa.user_id',
                'siswa.nisn',
                'siswa.nama',
                'siswa.kelas',
                'siswa.jenis_kelamin',
                'siswa.tanggal_lahir',
                'siswa.alamat',
                'siswa.created_at',
                'siswa.updated_at',
                'users.email as user_email',
            )
            ->selectRaw('count(absensi.id) as absensi_count')
            ->when($request->filled('q'), function ($query) use ($request) {
                $kataKunci = '%'.$request->input('q').'%';

                $query->where(function ($query) use ($kataKunci) {
                    $query->where('siswa.nama', 'like', $kataKunci)
                        ->orWhere('siswa.nisn', 'like', $kataKunci);
                });
            })
            ->when($request->filled('kelas'), fn ($query) => $query->where('siswa.kelas', $request->input('kelas'))
            )
            ->groupBy(
                'siswa.id',
                'siswa.user_id',
                'siswa.nisn',
                'siswa.nama',
                'siswa.kelas',
                'siswa.jenis_kelamin',
                'siswa.tanggal_lahir',
                'siswa.alamat',
                'siswa.created_at',
                'siswa.updated_at',
                'users.email',
            )
            ->orderBy('siswa.kelas')
            ->orderBy('siswa.nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.siswa.index', [
            'siswas' => $siswas,
            'kelasList' => $this->kelasList(),
        ]);
    }

    /**
     * Form tambah siswa.
     */
    public function create(): View
    {
        return view('admin.siswa.form', [
            'siswa' => null,
            'kelasList' => $this->kelasList(),
        ]);
    }

    /**
     * Simpan siswa baru beserta akun loginnya.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nisn' => ['required', 'string', 'digits:10', 'unique:siswa,nisn'],
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => User::ROLE_SISWA,
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nisn' => $data['nisn'],
                'nama' => $data['nama'],
                'kelas' => $data['kelas'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'alamat' => $data['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Form ubah data siswa.
     */
    public function edit(Siswa $siswa): View
    {
        return view('admin.siswa.form', [
            'siswa' => $siswa,
            'kelasList' => $this->kelasList(),
        ]);
    }

    /**
     * Perbarui data siswa dan akun loginnya.
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $data = $request->validate([
            'nisn' => [
                'required',
                'string',
                'digits:10',
                Rule::unique('siswa', 'nisn')->ignore($siswa->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($siswa->user_id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($data, $siswa) {
            $siswa->update([
                'nisn' => $data['nisn'],
                'nama' => $data['nama'],
                'kelas' => $data['kelas'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'alamat' => $data['alamat'] ?? null,
            ]);

            $user = User::find($siswa->user_id);

            if ($user !== null) {
                $userData = [
                    'name' => $data['nama'],
                    'email' => $data['email'],
                ];

                if (! empty($data['password'])) {
                    $userData['password'] = $data['password'];
                }

                $user->update($userData);
            }
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Hapus siswa beserta akun loginnya.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        if (Absensi::where('siswa_id', $siswa->id)->exists()) {
            return back()->with(
                'error',
                'Siswa tidak bisa dihapus karena sudah memiliki data absensi.'
            );
        }

        DB::transaction(function () use ($siswa) {
            $user = User::find($siswa->user_id);
            $siswa->delete();
            $user?->delete();
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Daftar kelas unik yang dipakai siswa.
     *
     * @return Collection<int, string>
     */
    private function kelasList(): Collection
    {
        return Siswa::query()
            ->whereNotNull('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');
    }
}

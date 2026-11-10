<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GuruController extends Controller
{
    /**
     * Daftar guru.
     */
    public function index(Request $request): View
    {
        $gurus = Guru::query()
            ->leftJoin('users', 'users.id', '=', 'guru.user_id')
            ->leftJoin('jadwal', 'jadwal.guru_id', '=', 'guru.id')
            ->select(
                'guru.id',
                'guru.nip',
                'guru.nama',
                'guru.no_telepon',
                'guru.created_at',
                'guru.updated_at',
                'users.email as user_email',
            )
            ->selectRaw('count(jadwal.id) as jadwal_count')
            ->when($request->filled('q'), function ($query) use ($request) {
                $kataKunci = '%'.$request->input('q').'%';
                $query->where(function ($query) use ($kataKunci) {
                    $query->where('guru.nama', 'like', $kataKunci)
                        ->orWhere('guru.nip', 'like', $kataKunci);
                });
            })
            ->groupBy(
                'guru.id',
                'guru.nip',
                'guru.nama',
                'guru.no_telepon',
                'guru.created_at',
                'guru.updated_at',
                'users.email'
            )
            ->orderBy('guru.nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.guru.index', compact('gurus'));
    }

    /**
     * Form tambah guru.
     */
    public function create(): View
    {
        return view('admin.guru.form', ['guru' => null]);
    }

    /**
     * Simpan guru baru beserta akun loginnya.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:30', 'unique:guru,nip'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'nip.unique' => 'NIP sudah digunakan.',
            'email.unique' => 'Email sudah dipakai.',
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => User::ROLE_GURU,
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'no_telepon' => $data['no_telepon'] ?? null,
            ]);
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Form ubah data guru.
     */
    public function edit(Guru $guru): View
    {
        return view('admin.guru.form', compact('guru'));
    }

    /**
     * Perbarui data guru dan akun loginnya.
     */
    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => [
                'required',
                'string',
                'max:30',
                Rule::unique('guru', 'nip')->ignore($guru->id),
            ],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($guru->user_id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($data, $guru) {
            $guru->update([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'no_telepon' => $data['no_telepon'] ?? null,
            ]);

            $user = User::find($guru->user_id);

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

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Hapus guru beserta akun loginnya.
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        if (Jadwal::where('guru_id', $guru->id)->exists()) {
            return back()->with(
                'error',
                'Guru tidak bisa dihapus karena masih memiliki jadwal mengajar.'
            );
        }

        DB::transaction(function () use ($guru) {
            $user = User::find($guru->user_id);
            $guru->delete();
            $user?->delete();
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}

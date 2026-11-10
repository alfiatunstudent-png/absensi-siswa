@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h3 mb-0">Data Siswa</h1>
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
            Tambah Siswa
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.siswa.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="q"
                        value="{{ request('q') }}" placeholder="Cari nama atau NISN...">
                </div>

                <div class="col-md-3">
                    <select name="kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas }}"
                                @selected(request('kelas') === $kelas)>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-auto">
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                </div>

                <div class="col-md-auto">
                    <a href="{{ route('admin.siswa.index') }}"
                        class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>JK</th>
                        <th>Email</th>
                        <th class="text-center">Absensi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($siswas as $siswa)
                        <tr>
                            <td>{{ $siswas->firstItem() + $loop->index }}</td>
                            <td><code>{{ $siswa->nisn }}</code></td>
                            <td>{{ $siswa->nama }}</td>
                            <td>
                                <span class="badge text-bg-light text-dark">
                                    {{ $siswa->kelas }}
                                </span>
                            </td>
                            <td>{{ $siswa->jenis_kelamin === 'L' ? 'L' : 'P' }}</td>
                            <td>{{ $siswa->user_email ?? '-' }}</td>
                            <td class="text-center">{{ $siswa->absensi_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.siswa.edit', $siswa) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    Ubah
                                </a>

                                <form method="POST"
                                    action="{{ route('admin.siswa.destroy', $siswa) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Hapus siswa {{ $siswa->nama }} beserta akun loginnya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $siswas->links() }}
        </div>
    </div>
@endsection
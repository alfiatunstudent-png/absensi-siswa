
@extends('layouts.app')

@section('title', 'Data Jadwal')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h3 mb-0">Jadwal Pelajaran</h1>

        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
            Tambah Jadwal
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="row g-2">
                <div class="col-md-3">
                    <select name="hari" class="form-select">
                        <option value="">Semua Hari</option>

                        @foreach (\App\Models\Jadwal::HARI as $hari)
                            <option value="{{ $hari }}" @selected(request('hari') === $hari)>
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="kelas" class="form-select">
                        <option value="">Semua Kelas</option>

                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas }}" @selected(request('kelas') === $kelas)>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-auto">
                    <button class="btn btn-outline-primary" type="submit">
                        Cari
                    </button>
                </div>

                <div class="col-md-auto">
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>

            {{-- Tambahan latihan nomor 3 --}}
            <div class="mt-3">
                <span class="text-muted">
                    Jumlah jadwal
                    {{ request('hari') ? 'hari ' . request('hari') : 'seluruh hari' }}:
                </span>

                <span class="badge text-bg-primary">
                    {{ $jumlahJadwal }} Jadwal
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($jadwals as $jadwal)
                        <tr>
                            <td>{{ $jadwals->firstItem() + $loop->index }}</td>

                            <td>
                                <span class="badge text-bg-{{ $jadwal->badgeHari() }}">
                                    {{ $jadwal->hari }}
                                </span>
                            </td>

                            <td>{{ $jadwal->jam() }}</td>
                            <td>{{ $jadwal->kelas }}</td>
                            <td>{{ $jadwal->mapel_nama }}</td>
                            <td>{{ $jadwal->guru_nama }}</td>

                            <td class="text-end">
                                <a href="{{ route('admin.jadwal.edit', $jadwal) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    Ubah
                                </a>

                                <form method="POST"
                                    action="{{ route('admin.jadwal.destroy', $jadwal) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Hapus jadwal {{ $jadwal->mapel_nama }} kelas {{ $jadwal->kelas }} hari {{ $jadwal->hari }}?')">
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
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data jadwal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $jadwals->links() }}
        </div>
    </div>
@endsection

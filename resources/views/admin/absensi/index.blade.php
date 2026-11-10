@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h3 mb-0">Data Absensi</h1>

        <a href="{{ route('admin.absensi.create') }}" class="btn btn-primary">
            Input Absensi
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.absensi.index') }}" class="row g-2">
                <div class="col-md-5">
                    <select name="jadwal_id" class="form-select">
                        <option value="">Semua Jadwal</option>

                        @foreach ($jadwals as $jadwal)
                            <option value="{{ $jadwal->id }}" @selected((int) request('jadwal_id') === $jadwal->id)>
                                {{ $jadwal->kelas }} &mdash; {{ $jadwal->hari }} {{ $jadwal->jam() }} &mdash; {{ $jadwal->mapel_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="date" class="form-control" name="tanggal" value="{{ request('tanggal') }}">
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>

                        @foreach (\App\Models\Absensi::STATUS as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>
                                {{ $label }}
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
                    <a href="{{ route('admin.absensi.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Jam</th>
                        <th>Siswa</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($absensis as $absensi)
                        <tr>
                            <td>{{ $absensis->firstItem() + $loop->index }}</td>
                            <td>{{ $absensi->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge text-bg-light text-dark">
                                    {{ $absensi->jadwal_kelas }}
                                </span>
                            </td>
                            <td>{{ $absensi->mapel_nama }}</td>
                            <td>{{ $absensi->jadwalJam() }}</td>
                            <td>{{ $absensi->siswa_nama }}</td>
                            <td>
                                <span class="badge text-bg-{{ $absensi->badgeStatus() }}">
                                    {{ $absensi->statusLabel() }}
                                </span>
                            </td>
                            <td>{{ $absensi->keterangan ?? '-' }}</td>

                            <td class="text-end">
                                <a href="{{ route('admin.absensi.edit', $absensi) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    Ubah
                                </a>

                                <form method="POST"
                                    action="{{ route('admin.absensi.destroy', $absensi) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Hapus catatan absensi {{ $absensi->siswa_nama }} tanggal {{ $absensi->tanggal->format('d/m/Y') }}?')">
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
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada data absensi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $absensis->links() }}
        </div>
    </div>
@endsection
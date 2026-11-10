@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h3 mb-1">Mata Pelajaran</h1>
            <p class="text-muted mb-0">
                {{ $siswa->nama }} &middot; Kelas {{ $siswa->kelas }}
            </p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Kode</th>
                        <th>Nama Mapel</th>
                        <th>Guru Pengampu</th>
                        <th>Jadwal</th>
                        <th class="text-center">Pertemuan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($jadwals as $jadwalMapel)
                        @php($pertama = $jadwalMapel->first())

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <code>{{ $pertama->mapel_kode }}</code>
                            </td>

                            <td>{{ $pertama->mapel_nama }}</td>

                            <td>
                                {{ $jadwalMapel->pluck('guru_nama')->unique()->implode(', ') }}
                            </td>

                            <td>
                                @foreach ($jadwalMapel as $jadwal)
                                    <span class="badge text-bg-light text-dark me-1">
                                        {{ $jadwal->hari }}, {{ $jadwal->jam() }}
                                    </span>
                                @endforeach
                            </td>

                            <td class="text-center">
                                {{ $jadwalMapel->count() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data mapel.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

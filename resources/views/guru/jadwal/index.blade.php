@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h3 mb-0">Jadwal Mengajar</h1>
        <span class="text-muted small">{{ $guru->nama }}</span>
    </div>

    <div class="row g-4">
        @forelse ($jadwals as $hari => $jadwalHari)
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 fw-semibold">
                        <span class="badge text-bg-{{ $jadwalHari->first()->badgeHari() }} me-1">
                            {{ $hari }}
                        </span>

                        {{ $jadwalHari->count() }} jam pelajaran
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jam</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($jadwalHari as $jadwal)
                                    <tr>
                                        <td>{{ $jadwal->jam() }}</td>
                                        <td>{{ $jadwal->kelas }}</td>
                                        <td>{{ $jadwal->mapel_nama }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">
                    Belum ada jadwal mengajar untuk Anda.
                </div>
            </div>
        @endforelse
    </div>
@endsection
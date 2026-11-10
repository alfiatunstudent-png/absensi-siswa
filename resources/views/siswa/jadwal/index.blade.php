@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h3 mb-1">Jadwal Pelajaran</h1>
            <p class="text-muted mb-0">
                {{ $siswa->nama }} &middot; Kelas {{ $siswa->kelas }}
            </p>
        </div>

        <span class="text-muted small">
            {{ now()->format('d/m/Y') }} &middot; {{ \App\Models\Jadwal::hariIni() }}
        </span>
    </div>

    <div class="row g-4">
        @forelse ($jadwals as $hari => $jadwalHari)
            @php
                $isToday = $hari === \App\Models\Jadwal::hariIni();
            @endphp

            <div class="col-md-6">
                <div class="card shadow-sm h-100 {{ $isToday ? 'border border-primary' : '' }}">
                    <div class="card-header bg-white py-3 fw-semibold d-flex justify-content-between align-items-center">
                        <span>
                            <span class="badge text-bg-{{ $jadwalHari->first()->badgeHari() }} me-1">
                                {{ $hari }}
                            </span>

                            {{ $jadwalHari->count() }} jam pelajaran
                        </span>

                        @if ($isToday)
                            <span class="badge text-bg-primary">Hari ini</span>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jam</th>
                                    <th>Mapel</th>
                                    <th>Guru</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($jadwalHari as $jadwal)
                                    <tr>
                                        <td>{{ $jadwal->jam() }}</td>
                                        <td>{{ $jadwal->mapel_nama }}</td>
                                        <td>{{ $jadwal->guru_nama }}</td>
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
                    Belum ada jadwal untuk kelas {{ $siswa->kelas }}.
                </div>
            </div>
        @endforelse
    </div>
@endsection
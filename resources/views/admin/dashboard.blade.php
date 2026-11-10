@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h3 mb-1">Dashboard Admin</h1>
        <p class="text-muted mb-0">Ringkasan data absensi siswa.</p>
    </div>
    <span class="text-muted small">
        {{ now()->format('d/m/Y') }} &middot; {{ \App\Models\Jadwal::hariIni() }}
    </span>
</div>

@php
$kartu = [
    ['label' => 'Guru', 'value' => $stats['guru'], 'route' => route('admin.guru.index')],
    ['label' => 'Siswa', 'value' => $stats['siswa'], 'route' => route('admin.siswa.index')],
    ['label' => 'Mapel', 'value' => $stats['mapel'], 'route' => route('admin.mapel.index')],
    ['label' => 'Jadwal', 'value' => $stats['jadwal'], 'route' => route('admin.jadwal.index')],
    ['label' => 'Total Absensi', 'value' => $stats['absensi'], 'route' => route('admin.absensi.index')],
    ['label' => 'Hadir Hari Ini', 'value' => $stats['hadir_hari_ini'], 'route' => route('admin.absensi.index')],
];
@endphp

<div class="row g-3 mb-4">
    @foreach ($kartu as $item)
        <div class="col-6 col-md-4 col-xl-2">
            <a href="{{ $item['route'] }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="fs-4 fw-semibold text-dark">{{ $item['value'] }}</div>
                        <div class="small text-muted">{{ $item['label'] }}</div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 fw-semibold">
                Jadwal Hari Ini ({{ \App\Models\Jadwal::hariIni() }})
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalHariIni as $jadwal)
                            <tr>
                                <td>{{ $jadwal->jam() }}</td>
                                <td>
                                    <span class="badge text-bg-light text-dark">
                                        {{ $jadwal->kelas }}
                                    </span>
                                </td>
                                <td>{{ $jadwal->mapel_nama }}</td>
                                <td>{{ $jadwal->guru_nama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Tidak ada jadwal hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 fw-semibold">
                Absensi Terbaru
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($absensiTerbaru as $absensi)
                    <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <div class="fw-semibold">{{ $absensi->siswa_nama }}</div>
                            <div class="small text-muted">
                                {{ $absensi->mapel_nama }} &middot; {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                            </div>
                        </div>
                        <span class="badge text-bg-{{ $absensi->badgeStatus() }}">
                            {{ $absensi->statusLabel() }}
                        </span>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">
                        Belum ada data absensi.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
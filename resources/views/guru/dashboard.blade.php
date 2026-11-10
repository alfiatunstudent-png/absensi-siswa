@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">Dashboard Guru</h1>
            <p class="text-muted mb-0">Selamat datang, {{ $guru->nama }}.</p>
        </div>

        <span class="text-muted small">
            {{ now()->format('d/m/Y') }} &middot; {{ \App\Models\Jadwal::hariIni() }}
        </span>
    </div>

    @php
        $kartu = [
            [
                'label' => 'Mapel Diampu',
                'value' => $stats['mapel'],
                'route' => route('guru.mapel.index')
            ],
            [
                'label' => 'Jadwal Mengajar',
                'value' => $stats['jadwal'],
                'route' => route('guru.jadwal.index')
            ],
            [
                'label' => 'Siswa Diajar',
                'value' => $stats['siswa'],
                'route' => route('guru.siswa.index')
            ],
            [
                'label' => 'Absensi Hari Ini',
                'value' => $stats['absensi_hari_ini'],
                'route' => route('guru.absensi.index')
            ],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach ($kartu as $item)
            <div class="col-6 col-xl-3">
                <a href="{{ $item['route'] }}" class="text-decoration-none">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="fs-4 fw-semibold text-dark">
                                {{ $item['value'] }}
                            </div>
                            <div class="small text-muted">
                                {{ $item['label'] }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- TAMBAHAN LATIHAN 1: Jumlah siswa per kelas --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <span class="fw-semibold">Jumlah Siswa per Kelas</span>
        </div>

        <div class="card-body">
            <div class="row g-3">
                @forelse ($siswaPerKelas as $kelas => $jumlah)
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="border rounded p-3 text-center">
                            <div class="fw-semibold text-dark">
                                {{ $kelas }}
                            </div>
                            <div class="fs-4 text-primary fw-semibold">
                                {{ $jumlah }}
                            </div>
                            <div class="small text-muted">
                                Siswa
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">
                        Belum ada kelas yang diajar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    {{-- AKHIR TAMBAHAN LATIHAN 1 --}}

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="fw-semibold">
                Jadwal Mengajar Hari Ini ({{ \App\Models\Jadwal::hariIni() }})
            </span>

            <a href="{{ route('guru.absensi.create') }}" class="btn btn-sm btn-primary">
                Input Absensi
            </a>
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
                    @forelse ($jadwalHariIni as $jadwal)
                        <tr>
                            <td>{{ $jadwal->jam() }}</td>
                            <td>
                                <span class="badge text-bg-light text-dark">
                                    {{ $jadwal->kelas }}
                                </span>
                            </td>
                            <td>{{ $jadwal->mapel_nama }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Tidak ada jadwal mengajar hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
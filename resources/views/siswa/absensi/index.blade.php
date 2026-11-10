
@extends('layouts.app')

@section('title', 'Absensi Saya')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h3 mb-1">Absensi Saya</h1>
            <p class="text-muted mb-0">
                {{ $siswa->nama }} &middot; Kelas {{ $siswa->kelas }}
            </p>
        </div>
    </div>

    @php
        $kartu = [
            'hadir' => ['Hadir', 'success'],
            'sakit' => ['Sakit', 'warning'],
            'izin' => ['Izin', 'info'],
            'alfa' => ['Alfa', 'danger'],
        ];
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="display-6 fw-semibold text-primary">
                        {{ $rekap['persentase'] }}%
                    </div>
                    <div class="small text-muted">
                        Kehadiran dari {{ $rekap['total'] }} pertemuan
                    </div>
                </div>
            </div>
        </div>

        @foreach ($kartu as $status => [$label, $warna])
            <div class="col-6 col-xl-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="fs-4 fw-semibold text-{{ $warna }}">
                            {{ $rekap[$status] }}
                        </div>
                        <div class="small text-muted">
                            {{ $label }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('siswa.absensi.index') }}" class="row g-2">
                <div class="col-md-3">
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
                    <a href="{{ route('siswa.absensi.index') }}" class="btn btn-outline-secondary">
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
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($absensis as $absensi)
                        <tr>
                            <td>{{ $absensis->firstItem() + $loop->index }}</td>
                            <td>{{ $absensi->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $absensi->mapel_nama }}</td>
                            <td>{{ $absensi->guru_nama }}</td>
                            <td>{{ $absensi->jadwalJam() }}</td>
                            <td>
                                <span class="badge text-bg-{{ $absensi->badgeStatus() }}">
                                    {{ $absensi->statusLabel() }}
                                </span>
                            </td>
                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
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

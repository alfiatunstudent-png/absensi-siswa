@extends('layouts.guest')

@section('title', 'Cek Absensi Anak')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-4">
                <h1 class="h3">Cek Absensi Anak</h1>
                <p class="text-muted mb-0">
                    Masukkan NISN siswa untuk melihat rekap kehadiran
                    dan riwayat absensi.
                </p>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('ortu.index') }}" class="row g-2 justify-content-center">
                        <div class="col-md-6">
                            <label for="nisn" class="form-label">NISN</label>

                            <input
                                type="text"
                                class="form-control form-control-lg"
                                id="nisn"
                                name="nisn"
                                value="{{ request('nisn') }}"
                                placeholder="Contoh: 0061234567"
                                required
                            >
                        </div>

                        <div class="col-md-auto d-flex align-items-end">
                            <button class="btn btn-primary btn-lg" type="submit">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($siswa !== null)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between gap-3">
                            <div>
                                <h2 class="h5 mb-1">{{ $siswa->nama }}</h2>
                                <p class="text-muted mb-0">
                                    NISN <code>{{ $siswa->nisn }}</code> &middot;
                                    Kelas {{ $siswa->kelas }}
                                </p>
                            </div>

                            <div class="text-lg-end">
                                <div class="display-6 fw-semibold text-primary">
                                    {{ $rekap['persentase'] }}%
                                </div>
                                <div class="text-muted small">
                                    Kehadiran dari {{ $rekap['total'] }} pertemuan
                                </div>
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

                        <div class="row g-2 mt-3">
                            @foreach ($kartu as $status => [$label, $warna])
                                <div class="col-6 col-md-3">
                                    <div class="border rounded-3 p-3 text-center h-100">
                                        <div class="fs-4 fw-semibold text-{{ $warna }}">
                                            {{ $rekap[$status] }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $label }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 fw-semibold">
                        Riwayat Absensi
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
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
                                        <td colspan="6" class="text-center text-muted py-4">
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
            @endif

            <p class="text-center mt-4 mb-0">
                <a href="{{ route('home') }}">&larr; Kembali ke beranda</a>
            </p>
        </div>
    </div>
@endsection
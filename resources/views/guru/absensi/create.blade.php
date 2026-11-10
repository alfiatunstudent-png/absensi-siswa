@extends('layouts.app')

@section('title', 'Input Absensi')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h3 mb-0">Input Absensi</h1>

        <a href="{{ route('guru.absensi.index') }}" class="btn btn-outline-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('guru.absensi.create') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label for="jadwal_id" class="form-label">Jadwal Mengajar</label>

                    <select name="jadwal_id" id="jadwal_id" class="form-select" required>
                        <option value="">Pilih jadwal...</option>

                        @foreach ($jadwals as $jadwal)
                            <option value="{{ $jadwal->id }}" @selected((int) request('jadwal_id') === $jadwal->id)>
                                {{ $jadwal->kelas }} &mdash; {{ $jadwal->hari }} {{ $jadwal->jam() }} &mdash; {{ $jadwal->mapel_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="tanggal" class="form-label">Tanggal</label>

                    <input
                        type="date"
                        class="form-control"
                        id="tanggal"
                        name="tanggal"
                        value="{{ $tanggal }}"
                        required
                    >
                </div>

                <div class="col-md-auto">
                    <button class="btn btn-primary" type="submit">
                        Tampilkan Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if ($selectedJadwal !== null)
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="fw-semibold">
                    {{ $selectedJadwal->kelas }} &mdash; {{ $selectedJadwal->mapel_nama }}

                    <span class="text-muted fw-normal">
                        ({{ $selectedJadwal->hari }}, {{ $selectedJadwal->jam() }} &middot;
                        {{ \Illuminate\Support\Carbon::parse($tanggal)->format('d/m/Y') }})
                    </span>
                </div>
            </div>

            {{-- Rekap jumlah status absensi --}}
            @php
                $rekapStatus = $absensiAda->countBy('status');
            @endphp

            <div class="card-body border-bottom d-flex flex-wrap gap-2">
                <span class="badge text-bg-success">
                    Hadir: {{ $rekapStatus->get('hadir', 0) }}
                </span>

                <span class="badge text-bg-warning">
                    Sakit: {{ $rekapStatus->get('sakit', 0) }}
                </span>

                <span class="badge text-bg-info">
                    Izin: {{ $rekapStatus->get('izin', 0) }}
                </span>

                <span class="badge text-bg-danger">
                    Alfa: {{ $rekapStatus->get('alfa', 0) }}
                </span>
            </div>

            <form method="POST" action="{{ route('guru.absensi.store') }}">
                @csrf

                <input type="hidden" name="jadwal_id" value="{{ $selectedJadwal->id }}">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th style="width: 180px">Status</th>
                                <th style="width: 260px">Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($siswas as $siswa)
                                @php
                                    $absen = $absensiAda->get($siswa->id);
                                    $absenKemarin = $absensiKemarin->get($siswa->id);
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><code>{{ $siswa->nisn }}</code></td>
                                    <td>{{ $siswa->nama }}</td>

                                    <td>
                                        <select
                                            name="status[{{ $siswa->id }}]"
                                            class="form-select form-select-sm js-status"
                                            data-kemarin-status="{{ $absenKemarin?->status ?? '' }}"
                                            required
                                        >
                                            @foreach (\App\Models\Absensi::STATUS as $value => $label)
                                                <option
                                                    value="{{ $value }}"
                                                    @selected(old("status.{$siswa->id}", $absen?->status ?? 'hadir') === $value)
                                                >
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input
                                            type="text"
                                            class="form-control form-control-sm js-keterangan"
                                            name="keterangan[{{ $siswa->id }}]"
                                            value="{{ old("keterangan.{$siswa->id}", $absen?->keterangan) }}"
                                            data-kemarin-keterangan="{{ $absenKemarin?->keterangan ?? '' }}"
                                            placeholder="Opsional"
                                        >
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Tidak ada siswa pada kelas {{ $selectedJadwal->kelas }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($siswas->isNotEmpty())
                    <div class="card-footer bg-white d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="submit">
                            Simpan Absensi
                        </button>

                        <button class="btn btn-outline-success" type="button" id="tandaiHadir">
                            Tandai Semua Hadir
                        </button>

                        <button class="btn btn-outline-danger" type="button" id="tandaiAlfa">
                            Tandai Semua Alfa
                        </button>

                        <button class="btn btn-outline-primary" type="button" id="salinKemarin">
                            Salin Tanggal Kemarin
                        </button>
                    </div>
                @endif
            </form>
        </div>
    @else
        <div class="alert alert-info">
            Pilih jadwal dan tanggal terlebih dahulu, lalu klik <strong>Tampilkan Siswa</strong>.
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.getElementById('tandaiHadir')?.addEventListener('click', () => {
            document.querySelectorAll('.js-status').forEach((select) => {
                select.value = 'hadir';
            });
        });

        document.getElementById('tandaiAlfa')?.addEventListener('click', () => {
            document.querySelectorAll('.js-status').forEach((select) => {
                select.value = 'alfa';
            });
        });

        document.getElementById('salinKemarin')?.addEventListener('click', () => {
            document.querySelectorAll('.js-status').forEach((select) => {
                const statusKemarin = select.dataset.kemarinStatus;

                if (statusKemarin) {
                    select.value = statusKemarin;
                }
            });

            document.querySelectorAll('.js-keterangan').forEach((input) => {
                input.value = input.dataset.kemarinKeterangan || '';
            });
        });
    </script>
@endpush

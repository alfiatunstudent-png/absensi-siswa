@extends('layouts.app')

@section('title', 'Ubah Absensi')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 fw-semibold">
                    Ubah Catatan Absensi
                </div>

                <div class="card-body">
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Siswa</dt>
                        <dd class="col-sm-8">
                            {{ $absensi->siswa_nama }} (<code>{{ $absensi->siswa_nisn }}</code>)
                        </dd>

                        <dt class="col-sm-4">Jadwal</dt>
                        <dd class="col-sm-8">
                            {{ $absensi->jadwal_kelas }} &mdash; {{ $absensi->mapel_nama }}
                            ({{ $absensi->jadwal_hari }}, {{ $absensi->jadwalJam() }})
                        </dd>

                        <dt class="col-sm-4">Tanggal</dt>
                        <dd class="col-sm-8">
                            {{ $absensi->tanggal->format('d/m/Y') }}
                        </dd>
                    </dl>

                    <form method="POST" action="{{ route('guru.absensi.update', $absensi) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>

                            <select class="form-select" id="status" name="status" required>
                                @foreach (\App\Models\Absensi::STATUS as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        @selected(old('status', $absensi->status) === $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="keterangan" class="form-label">Keterangan</label>

                            <input
                                type="text"
                                class="form-control"
                                id="keterangan"
                                name="keterangan"
                                value="{{ old('keterangan', $absensi->keterangan) }}"
                                placeholder="Opsional"
                            >
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">
                                Simpan
                            </button>

                            <a href="{{ route('guru.absensi.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
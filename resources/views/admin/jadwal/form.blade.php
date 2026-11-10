@extends('layouts.app')

@php
    $editing = $jadwal !== null;
@endphp

@section('title', $editing ? 'Ubah Jadwal' : 'Tambah Jadwal')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 fw-semibold">
                    {{ $editing ? 'Ubah' : 'Tambah' }} Jadwal Pelajaran
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ $editing ? route('admin.jadwal.update', $jadwal) : route('admin.jadwal.store') }}">
                        @csrf

                        @if ($editing)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mapel_id" class="form-label">Mata Pelajaran</label>

                                <select class="form-select" id="mapel_id" name="mapel_id" required>
                                    <option value="">Pilih mapel...</option>

                                    @foreach ($mapelList as $mapel)
                                        <option value="{{ $mapel->id }}" @selected((int) old('mapel_id', $jadwal?->mapel_id) === $mapel->id)>
                                            {{ $mapel->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="guru_id" class="form-label">Guru Pengajar</label>

                                <select class="form-select" id="guru_id" name="guru_id" required>
                                    <option value="">Pilih guru...</option>

                                    @foreach ($guruList as $guru)
                                        <option value="{{ $guru->id }}" @selected((int) old('guru_id', $jadwal?->guru_id) === $guru->id)>
                                            {{ $guru->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="kelas" class="form-label">Kelas</label>

                                <input type="text" class="form-control" id="kelas"
                                    name="kelas" list="daftarKelas"
                                    value="{{ old('kelas', $jadwal?->kelas) }}"
                                    placeholder="Contoh: X IPA 1" required>

                                <datalist id="daftarKelas">
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="hari" class="form-label">Hari</label>

                                <select class="form-select" id="hari" name="hari" required>
                                    <option value="">Pilih hari...</option>

                                    @foreach (\App\Models\Jadwal::HARI as $hari)
                                        <option value="{{ $hari }}" @selected(old('hari', $jadwal?->hari) === $hari)>
                                            {{ $hari }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>

                                <input type="time" class="form-control" id="jam_mulai"
                                    name="jam_mulai"
                                    value="{{ old('jam_mulai', $jadwal ? substr($jadwal->jam_mulai, 0, 5) : '') }}" required>
                            </div>
                        </div>

                        <div class="mb-4" style="max-width: 220px;">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>

                            <input type="time" class="form-control" id="jam_selesai"
                                name="jam_selesai"
                                value="{{ old('jam_selesai', $jadwal ? substr($jadwal->jam_selesai, 0, 5) : '') }}" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">
                                Simpan
                            </button>

                            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@php
    $editing = $siswa !== null;
@endphp

@section('title', $editing ? 'Ubah Siswa' : 'Tambah Siswa')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 fw-semibold">
                    {{ $editing ? 'Ubah' : 'Tambah' }} Data Siswa
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ $editing ? route('admin.siswa.update', $siswa) : route('admin.siswa.store') }}">
                        @csrf
                        @if ($editing)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nisn" class="form-label">NISN (10 digit)</label>
                                <input type="text" class="form-control" id="nisn" name="nisn"
                                    inputmode="numeric" maxlength="10"
                                    value="{{ old('nisn', $siswa?->nisn) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ old('nama', $siswa?->nama) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas"
                                    list="daftarKelas"
                                    placeholder="Contoh: X IPA 1"
                                    value="{{ old('kelas', $siswa?->kelas) }}" required>

                                <datalist id="daftarKelas">
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih...</option>
                                    <option value="L" @selected(old('jenis_kelamin', $siswa?->jenis_kelamin) === 'L')>Laki-laki</option>
                                    <option value="P" @selected(old('jenis_kelamin', $siswa?->jenis_kelamin) === 'P')>Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $siswa?->tanggal_lahir?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2">{{ old('alamat', $siswa?->alamat) }}</textarea>
                        </div>

                        <hr class="my-4">

                        <h2 class="h6 text-muted mb-3">Akun Login</h2>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $siswa?->emailAkun()) }}" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">
                                    Password {{ $editing ? '(kosongkan jika tidak diubah)' : '' }}
                                </label>
                                <input type="password" class="form-control" id="password" name="password"
                                    {{ $editing ? '' : 'required' }} minlength="6"
                                    autocomplete="new-password">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Simpan</button>
                            <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
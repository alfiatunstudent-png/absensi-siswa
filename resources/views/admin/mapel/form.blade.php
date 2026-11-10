@extends('layouts.app')

@php
    $editing = $mapel !== null;
@endphp

@section('title', $editing ? 'Ubah Mapel' : 'Tambah Mapel')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 fw-semibold">
                    {{ $editing ? 'Ubah' : 'Tambah' }} Mata Pelajaran
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ $editing ? route('admin.mapel.update', $mapel) : route('admin.mapel.store') }}">
                        @csrf

                        @if ($editing)
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode</label>
                            <input type="text" class="form-control" id="kode" name="kode"
                                value="{{ old('kode', $mapel?->kode) }}"
                                placeholder="Contoh: MAT-01" required>
                        </div>

                        <div class="mb-4">
                            <label for="nama" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ old('nama', $mapel?->nama) }}"
                                placeholder="Contoh: Matematika" required>
                            @error('kode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">
                                Simpan
                            </button>

                            <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
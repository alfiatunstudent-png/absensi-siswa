@extends('layouts.app')
@php
$editing = $guru !== null;
@endphp
@section('title', $editing ? 'Ubah Guru' : 'Tambah Guru')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card shadow-sm">
<div class="card-header bg-white py-3 fw-semibold">
{{ $editing ? 'Ubah' : 'Tambah' }} Data Guru
</div>
<div class="card-body">
<form method="POST" action="{{ $editing ? route('admin.guru.update',
$guru) : route('admin.guru.store') }}">
@csrf
@if ($editing)
@method('PUT')
@endif
<div class="row">
<div class="col-md-6 mb-3">
<label for="nama" class="form-label">Nama Lengkap</label>
<input type="text" class="form-control" id="nama" name="nama"
value="{{ old('nama', $guru?->nama) }}" required>
</div>

<div class="col-md-6 mb-3">

<label for="nip" class="form-label">NIP</label>

<input type="text" class="form-control" id="nip" name="nip"
value="{{ old('nip', $guru?->nip) }}" required>
</div>
</div>
<div class="mb-3">
<label for="no_telepon" class="form-label">No. Telepon</label>
<input type="text" class="form-control" id="no_telepon"

name="no_telepon"
value="{{ old('no_telepon', $guru?->no_telepon) }}">
</div>
<hr class="my-4">
<h2 class="h6 text-muted mb-3">Akun Login</h2>
<div class="mb-3">
<label for="email" class="form-label">Email</label>

<input type="email" class="form-control" id="email" name="email"
value="{{ old('email', $guru?->emailAkun()) }}" required>
</div>
<div class="mb-4">
<label for="password" class="form-label">
Password {{ $editing ? '(kosongkan jika tidak diubah)' : '' }}
</label>

<input type="password" class="form-control" id="password"

name="password"
{{ $editing ? '' : 'required' }} minlength="6"
autocomplete="new-password">
</div>
<div class="d-flex gap-2">
<button class="btn btn-primary" type="submit">
Simpan
</button>

<a href="{{ route('admin.guru.index') }}" class="btn btn-outline-
secondary">Batal</a>

</div>
</form>
</div>
</div>
</div>
</div>
@endsection
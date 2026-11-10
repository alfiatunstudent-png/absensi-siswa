@extends('layouts.app')
@section('title', 'Data Guru')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
<h1 class="h3 mb-0">Data Guru</h1>
<a href="{{ route('admin.guru.create') }}" class="btn btn-primary">
Tambah Guru
</a>
</div>
<div class="card shadow-sm">
<div class="card-body border-bottom">
<form method="GET" action="{{ route('admin.guru.index') }}" class="row g-2">
<div class="col-md-4">
<input type="text" class="form-control" name="q" value="{{ request('q')
}}" placeholder="Cari nama atau NIP...">
</div>
<div class="col-md-auto">
<button class="btn btn-outline-primary" type="submit">Cari</button>
</div>
<div class="col-md-auto">

<a href="{{ route('admin.guru.index') }}" class="btn btn-outline-
secondary">Reset</a>

</div>
</form>
</div>
<div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead class="table-light">
<tr>
<th style="width: 60px">#</th>

<th>NIP</th>
<th>Nama</th>
<th>No. Telepon</th>
<th>Email</th>
<th class="text-center">Jadwal</th>
<th class="text-end">Aksi</th>

</tr>
</thead>
<tbody>
@forelse ($gurus as $guru)
<tr>
<td>{{ $gurus->firstItem() + $loop->index }}</td>
<td><code>{{ $guru->nip }}</code></td>

<td>{{ $guru->nama }}</td>
<td>{{ $guru->no_telepon ?? '-' }}</td>
<td>{{ $guru->user_email ?? '-' }}</td>
<td class="text-center">{{ $guru->jadwal_count }}</td>
<td class="text-end">
<a href="{{ route('admin.guru.edit', $guru) }}" class="btn
btn-sm btn-outline-warning">
Ubah
</a>

<form method="POST" action="{{ route('admin.guru.destroy',

$guru) }}" class="d-inline"
onsubmit="return confirm('Hapus guru {{ $guru->nama }}
beserta akun loginnya?')">
@csrf

@method('DELETE')
<button class="btn btn-sm btn-outline-danger"

type="submit">
Hapus
</button>
</form>
</td>
</tr>
@empty
<tr>
<td colspan="7" class="text-center text-muted py-4">Belum ada data
guru.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<div class="card-footer bg-white">
{{ $gurus->links() }}
</div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Data Mapel')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h3 mb-0">Mata Pelajaran</h1>
        <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary">
            Tambah Mapel
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.mapel.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama mapel...">
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                </div>
                <div class="col-md-auto">
                    <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Kode</th>
                        <th>Nama Mapel</th>
                        <th>Dibuat</th>
                        <th class="text-center">Jadwal</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mapels as $mapel)
                        <tr>
                            <td>{{ $mapels->firstItem() + $loop->index }}</td>
                            <td><code>{{ $mapel->kode }}</code></td>
                            <td>{{ $mapel->nama }}</td>
                            <td>{{ $mapel->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $mapel->jadwal_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.mapel.edit', $mapel) }}" class="btn btn-sm btn-outline-warning">
                                    Ubah
                                </a>
                                <form method="POST" action="{{ route('admin.mapel.destroy', $mapel) }}" class="d-inline"
                                    onsubmit="return confirm('Hapus mapel {{ $mapel->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data mapel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $mapels->links() }}
        </div>
    </div>
@endsection
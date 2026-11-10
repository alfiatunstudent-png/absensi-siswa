@extends('layouts.app')

@section('title', 'Mapel Diampu')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h3 mb-0">Mata Pelajaran Diampu</h1>
        <span class="text-muted small">{{ $guru->nama }}</span>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Kode</th>
                        <th>Nama Mapel</th>
                        <th class="text-center">Jumlah Kelas/Jadwal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mapels as $mapel)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $mapel->kode }}</code></td>
                            <td>{{ $mapel->nama }}</td>
                            <td class="text-center">{{ $mapel->jumlah_kelas }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada mapel yang diampu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
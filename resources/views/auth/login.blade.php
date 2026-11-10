@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1 text-center">Masuk</h1>
                    <p class="text-muted text-center small mb-4">
                        Gunakan akun yang diberikan admin sekolah.
                    </p>

                    <form method="POST" action="{{ route('login.store') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                            >
                        </div>

                        <div class="form-check mb-4">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember"
                                value="1"
                            >
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h2 class="h6 text-muted">Akun demo (password: <code>password</code>)</h2>
                    <ul class="mb-0">
                        <li>Admin: <code>admin@absensi.test</code></li>
                        <li>Guru: <code>guru@absensi.test</code></li>
                        <li>Siswa: <code>siswa@absensi.test</code></li>
                    </ul>
                </div>
            </div>

            <p class="text-center mt-3 mb-0">
                <a href="{{ route('home') }}">Kembali ke beranda</a>
            </p>
        </div>
    </div>
@endsection

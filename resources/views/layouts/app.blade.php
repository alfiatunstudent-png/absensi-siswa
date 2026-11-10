<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Absensi Siswa') &middot; {{ config('app.name') }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">
<style>
body {
background-color: #f4f6fb;
}
.navbar-brand {
letter-spacing: .3px;
}
.card {
border: 0;
}
</style>
@stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
<div class="container">
<a class="navbar-brand fw-semibold" href="{{ route('home') }}">
{{ config('app.name') }}
</a>

<button class="navbar-toggler" type="button"
data-bs-toggle="collapse"
data-bs-target="#mainNavbar"
aria-controls="mainNavbar"
aria-expanded="false"
aria-label="Buka navigasi">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="mainNavbar">
<ul class="navbar-nav me-auto">
@auth
@if (auth()->user()->isAdmin())
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
Dashboard
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}" href="{{ route('admin.jadwal.index') }}">
Jadwal
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}" href="{{ route('admin.mapel.index') }}">
Mapel
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}" href="{{ route('admin.guru.index') }}">
Guru
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}" href="{{ route('admin.siswa.index') }}">
Siswa
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}" href="{{ route('admin.absensi.index') }}">
Absensi
</a>
</li>
@elseif (auth()->user()->isGuru())
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}" href="{{ route('guru.dashboard') }}">
Dashboard
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('guru.jadwal.*') ? 'active' : '' }}" href="{{ route('guru.jadwal.index') }}">
Jadwal
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('guru.mapel.*') ? 'active' : '' }}" href="{{ route('guru.mapel.index') }}">
Mapel
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('guru.siswa.*') ? 'active' : '' }}" href="{{ route('guru.siswa.index') }}">
Siswa
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('guru.absensi.*') ? 'active' : '' }}" href="{{ route('guru.absensi.index') }}">
Absensi
</a>
</li>
@else
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('siswa.jadwal.*') ? 'active' : '' }}" href="{{ route('siswa.jadwal.index') }}">
Jadwal
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('siswa.mapel.*') ? 'active' : '' }}" href="{{ route('siswa.mapel.index') }}">
Mapel
</a>
</li>

<li class="nav-item">
<a class="nav-link {{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}" href="{{ route('siswa.absensi.index') }}">
Absensi
</a>
</li>
@endif
@else
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('ortu.*') ? 'active' : '' }}" href="{{ route('ortu.index') }}">
Cek Absensi Anak
</a>
</li>
@endauth
</ul>

<ul class="navbar-nav align-items-lg-center">
@auth
<li class="nav-item me-lg-3">
<span class="navbar-text small">
{{ auth()->user()->name }}
<span class="badge text-bg-light text-primary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
</span>
</li>

<li class="nav-item">
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="btn btn-outline-light btn-sm">
Keluar
</button>
</form>
</li>
@else
<li class="nav-item">
<a class="btn btn-light btn-sm text-primary fw-semibold" href="{{ route('login') }}">
Masuk
</a>
</li>
@endauth
</ul>
</div>
</div>
</nav>

<main class="container pb-5">
@include('partials.flash')
@include('partials.errors')
@yield('content')
</main>

<footer class="text-center text-muted small py-4">
{{ config('app.name') }} &middot; Sistem Absensi Siswa
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

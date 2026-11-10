<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrtuController;
use App\Http\Controllers\Siswa;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/cek-absensi', [OrtuController::class, 'index'])->name('ortu.index');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Log Viewer khusus Admin
    Route::get('/logs', [LogViewerController::class, 'index'])->name('logs');

    Route::resource('mapel', Admin\MapelController::class)
        ->parameters(['mapel' => 'mapel'])
        ->except(['show']);

    Route::resource('guru', Admin\GuruController::class)
        ->parameters(['guru' => 'guru'])
        ->except(['show']);

    Route::resource('siswa', Admin\SiswaController::class)
        ->parameters(['siswa' => 'siswa'])
        ->except(['show']);

    Route::resource('jadwal', Admin\JadwalController::class)
        ->parameters(['jadwal' => 'jadwal'])
        ->except(['show']);

    Route::resource('absensi', Admin\AbsensiController::class)
        ->parameters(['absensi' => 'absensi'])
        ->except(['show']);
});

Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [Guru\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [Guru\JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/mapel', [Guru\MapelController::class, 'index'])->name('mapel.index');
    Route::get('/siswa', [Guru\SiswaController::class, 'index'])->name('siswa.index');

    Route::resource('absensi', Guru\AbsensiController::class)
        ->parameters(['absensi' => 'absensi'])
        ->except(['show']);
});

Route::middleware(['auth', 'role:siswa,admin'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/jadwal', [Siswa\JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/mapel', [Siswa\MapelController::class, 'index'])->name('mapel.index');
        Route::get('/absensi', [Siswa\AbsensiController::class, 'index'])->name('absensi.index');
    });

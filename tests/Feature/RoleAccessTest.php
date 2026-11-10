<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $this->get(route('guru.dashboard'))
            ->assertRedirect(route('login'));

        $this->get(route('siswa.absensi.index'))
            ->assertRedirect(route('login'));
    }

    public function test_guru_tidak_bisa_mengakses_halaman_admin(): void
    {
        $guru = User::factory()->create([
            'role' => User::ROLE_GURU,
        ]);

        $this->actingAs($guru)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($guru)
            ->get(route('admin.siswa.index'))
            ->assertForbidden();
    }

    public function test_siswa_tidak_bisa_mengakses_halaman_guru(): void
    {
        $siswa = User::factory()->create([
            'role' => User::ROLE_SISWA,
        ]);

        $this->actingAs($siswa)
            ->get(route('guru.dashboard'))
            ->assertForbidden();

        $this->actingAs($siswa)
            ->get(route('guru.absensi.index'))
            ->assertForbidden();
    }

    public function test_siswa_tidak_bisa_mengakses_halaman_admin(): void
    {
        $siswa = User::factory()->create([
            'role' => User::ROLE_SISWA,
        ]);

        $this->actingAs($siswa)
            ->get(route('admin.jadwal.index'))
            ->assertForbidden();
    }
}

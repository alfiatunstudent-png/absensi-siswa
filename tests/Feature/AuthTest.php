<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_admin_diarahkan_ke_dashboard_admin(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_guru_diarahkan_ke_dashboard_guru(): void
    {
        $guru = User::factory()->create([
            'role' => User::ROLE_GURU,
        ]);

        $response = $this->post('/login', [
            'email' => $guru->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('guru.dashboard'));
        $this->assertAuthenticatedAs($guru);
    }

    public function test_login_siswa_diarahkan_ke_jadwal_siswa(): void
    {
        $siswa = User::factory()->create([
            'role' => User::ROLE_SISWA,
        ]);

        $response = $this->post('/login', [
            'email' => $siswa->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('siswa.jadwal.index'));
        $this->assertAuthenticatedAs($siswa);
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'password-salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_menghapus_sesi(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}

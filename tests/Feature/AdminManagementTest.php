<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_admin_bisa_menambah_mengubah_dan_menghapus_mapel(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.mapel.store'), [
                'kode' => 'MAT-01',
                'nama' => 'Matematika',
            ])
            ->assertRedirect(route('admin.mapel.index'));

        $this->assertDatabaseHas('mapel', [
            'kode' => 'MAT-01',
            'nama' => 'Matematika',
        ]);

        $mapel = Mapel::first();

        $this->actingAs($this->admin)
            ->put(route('admin.mapel.update', $mapel), [
                'kode' => 'MAT-02',
                'nama' => 'Matematika Lanjut',
            ])
            ->assertRedirect(route('admin.mapel.index'));

        $this->assertDatabaseHas('mapel', [
            'id' => $mapel->id,
            'kode' => 'MAT-02',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.mapel.destroy', $mapel))
            ->assertRedirect(route('admin.mapel.index'));

        $this->assertDatabaseMissing('mapel', [
            'id' => $mapel->id,
        ]);
    }

    public function test_mapel_tidak_bisa_dihapus_jika_masih_dipakai_jadwal(): void
    {
        $mapel = Mapel::factory()->create();

        Jadwal::factory()->create([
            'mapel_id' => $mapel->id,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.mapel.destroy', $mapel))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('mapel', [
            'id' => $mapel->id,
        ]);
    }

    public function test_admin_bisa_menambah_guru_beserta_akun_loginnya(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.guru.store'), [
                'nama' => 'Budi Santoso',
                'nip' => '198501012010011001',
                'no_telepon' => '081234567001',
                'email' => 'budi@absensi.test',
                'password' => 'rahasia123',
            ])
            ->assertRedirect(route('admin.guru.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'budi@absensi.test',
            'role' => User::ROLE_GURU,
        ]);

        $user = User::where('email', 'budi@absensi.test')->first();

        $this->assertDatabaseHas('guru', [
            'nip' => '198501012010011001',
            'nama' => 'Budi Santoso',
            'user_id' => $user->id,
        ]);
    }

    public function test_admin_bisa_menambah_siswa_beserta_akun_loginnya(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.siswa.store'), [
                'nisn' => '0061234567',
                'nama' => 'Andi Pratama',
                'kelas' => 'X IPA 1',
                'jenis_kelamin' => 'L',
                'email' => 'andi@absensi.test',
                'password' => 'rahasia123',
            ])
            ->assertRedirect(route('admin.siswa.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'andi@absensi.test',
            'role' => User::ROLE_SISWA,
        ]);

        $this->assertDatabaseHas('siswa', [
            'nisn' => '0061234567',
            'nama' => 'Andi Pratama',
            'kelas' => 'X IPA 1',
        ]);
    }

    public function test_admin_bisa_menambah_jadwal(): void
    {
        $mapel = Mapel::factory()->create();
        $guru = Guru::factory()->create();

        $this->actingAs($this->admin)
            ->post(route('admin.jadwal.store'), [
                'mapel_id' => $mapel->id,
                'guru_id' => $guru->id,
                'kelas' => 'X IPA 1',
                'hari' => 'Senin',
                'jam_mulai' => '07:00',
                'jam_selesai' => '08:30',
            ])
            ->assertRedirect(route('admin.jadwal.index'));

        $this->assertDatabaseHas('jadwal', [
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'kelas' => 'X IPA 1',
            'hari' => 'Senin',
        ]);
    }

    public function test_jadwal_bentrok_guru_ditolak(): void
    {
        $jadwal = Jadwal::factory()->create([
            'kelas' => 'X IPA 1',
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:30',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.jadwal.store'), [
                'mapel_id' => Mapel::factory()->create()->id,
                'guru_id' => $jadwal->guru_id,
                'kelas' => 'X IPA 2',
                'hari' => 'Senin',
                'jam_mulai' => '07:00',
                'jam_selesai' => '08:30',
            ])
            ->assertSessionHasErrors('jam_mulai');

        $this->assertDatabaseCount('jadwal', 1);
    }

    public function test_admin_bisa_melihat_semua_halaman_kelolaan(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.mapel.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.guru.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.siswa.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.jadwal.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.absensi.index'))
            ->assertOk();
    }

    public function test_siswa_tidak_bisa_dihapus_jika_sudah_punya_absensi(): void
    {
        $siswa = Siswa::factory()->create();

        Absensi::factory()->create([
            'siswa_id' => $siswa->id,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.siswa.destroy', $siswa))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
        ]);
    }
}

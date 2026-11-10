<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelSiswaDanOrtuTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_melihat_jadwal_kelasnya(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SISWA,
        ]);

        $siswa = Siswa::factory()->create([
            'user_id' => $user->id,
            'kelas' => 'X IPA 1',
        ]);

        $jadwal = Jadwal::factory()->create([
            'kelas' => 'X IPA 1',
        ]);

        $response = $this->actingAs($user)
            ->get(route('siswa.jadwal.index'));

        $mapel = Mapel::findOrFail($jadwal->mapel_id);

        $response->assertOk();
        $response->assertSee($mapel->nama);
    }

    public function test_siswa_hanya_melihat_absensi_miliknya_sendiri(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SISWA,
        ]);

        $siswa = Siswa::factory()->create([
            'user_id' => $user->id,
            'kelas' => 'X IPA 1',
        ]);

        $siswaLain = Siswa::factory()->create([
            'kelas' => 'X IPA 1',
        ]);

        $jadwal = Jadwal::factory()->create([
            'kelas' => 'X IPA 1',
        ]);

        Absensi::factory()->create([
            'jadwal_id' => $jadwal->id,
            'siswa_id' => $siswa->id,
            'status' => 'hadir',
        ]);

        Absensi::factory()->create([
            'jadwal_id' => $jadwal->id,
            'siswa_id' => $siswaLain->id,
            'status' => 'alfa',
        ]);

        $response = $this->actingAs($user)
            ->get(route('siswa.absensi.index'));

        $response->assertOk();
        $response->assertSee('Hadir');

        $response->assertViewHas(
            'rekap',
            fn (array $rekap) => $rekap['total'] === 1
        );

        $response->assertViewHas(
            'absensis',
            fn ($absensis) => $absensis->total() === 1
        );
    }

    public function test_halaman_cek_absensi_terbuka_untuk_umum(): void
    {
        $this->get(route('ortu.index'))->assertOk();
    }

    public function test_orang_tua_bisa_mencari_berdasarkan_nisn(): void
    {
        $siswa = Siswa::factory()->create([
            'nisn' => '0061234567',
            'nama' => 'Andi Pratama',
        ]);

        $response = $this->get(route('ortu.index', [
            'nisn' => '0061234567',
        ]));

        $response->assertOk();
        $response->assertSee('Andi Pratama');
        $response->assertSee('0061234567');
    }

    public function test_nisn_tidak_ditemukan_menampilkan_pesan(): void
    {
        $response = $this->get(route('ortu.index', [
            'nisn' => '9999999999',
        ]));

        $response->assertOk();
        $response->assertSee('tidak ditemukan');
    }

    public function test_landing_page_dan_halaman_login_tampil(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Sistem Absensi Siswa');

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Isi data contoh: guru, siswa, mapel, jadwal, dan absensi.
     */
    public function run(): void
    {
        $gurus = $this->seedGuru();
        $mapels = $this->seedMapel();
        $this->seedSiswa();
        $jadwals = $this->seedJadwal($gurus, $mapels);
        $this->seedAbsensi($jadwals);
    }

    /**
     * @return array<string, Guru>
     */
    private function seedGuru(): array
    {
        $data = [
            [
                'nip' => '198501012010011001',
                'nama' => 'Budi Santoso, S.Pd',
                'no_telepon' => '081234567001',
                'email' => 'budi@absensi.test',
            ],
            [
                'nip' => '198703152012012002',
                'nama' => 'Siti Aminah, M.Pd',
                'no_telepon' => '081234567002',
                'email' => 'siti@absensi.test',
            ],
            [
                'nip' => '199001202015031003',
                'nama' => 'Ahmad Fauzi, S.Kom',
                'no_telepon' => '081234567003',
                'email' => 'ahmad@absensi.test',
            ],
            [
                'nip' => '199205102017052004',
                'nama' => 'Dewi Lestari, S.Pd',
                'no_telepon' => '081234567004',
                'email' => 'dewi@absensi.test',
            ],
        ];

        $gurus = [];

        foreach ($data as $item) {
            $user = User::updateOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['nama'],
                    'password' => 'password',
                    'role' => User::ROLE_GURU,
                ],
            );

            $gurus[$item['nip']] = Guru::updateOrCreate(
                ['nip' => $item['nip']],
                [
                    'user_id' => $user->id,
                    'nama' => $item['nama'],
                    'no_telepon' => $item['no_telepon'],
                ],
            );
        }

        return $gurus;
    }

    /**
     * @return array<string, Mapel>
     */
    private function seedMapel(): array
    {
        $data = [
            ['kode' => 'MAT-01', 'nama' => 'Matematika'],
            ['kode' => 'BIN-01', 'nama' => 'Bahasa Indonesia'],
            ['kode' => 'BIG-01', 'nama' => 'Bahasa Inggris'],
            ['kode' => 'IPA-01', 'nama' => 'Ilmu Pengetahuan Alam'],
            ['kode' => 'KOM-01', 'nama' => 'Informatika'],
            ['kode' => 'PJK-01', 'nama' => 'PJOK'],
        ];

        $mapels = [];

        foreach ($data as $item) {
            $mapels[$item['kode']] = Mapel::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }

        return $mapels;
    }

    private function seedSiswa(): void
    {
        $data = [
            [
                'nisn' => '0061234567',
                'nama' => 'Andi Pratama',
                'kelas' => 'X IPA 1',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-01-15',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
            ],
            [
                'nisn' => '0061234568',
                'nama' => 'Bunga Amelia Putri',
                'kelas' => 'X IPA 1',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-03-22',
                'alamat' => 'Jl. Melati No. 5, Jakarta',
            ],
            [
                'nisn' => '0061234569',
                'nama' => 'Cahyo Nugroho',
                'kelas' => 'X IPA 1',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2009-11-08',
                'alamat' => 'Jl. Kenanga No. 21, Jakarta',
            ],
            [
                'nisn' => '0061234570',
                'nama' => 'Dinda Ayu Safitri',
                'kelas' => 'X IPA 1',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-05-30',
                'alamat' => 'Jl. Anggrek No. 3, Jakarta',
            ],
            [
                'nisn' => '0061234571',
                'nama' => 'Eko Prasetyo',
                'kelas' => 'X IPA 1',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-02-14',
                'alamat' => 'Jl. Mawar No. 17, Jakarta',
            ],
            [
                'nisn' => '0061234572',
                'nama' => 'Fitri Handayani',
                'kelas' => 'X IPA 1',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-07-19',
                'alamat' => 'Jl. Dahlia No. 9, Jakarta',
            ],
            [
                'nisn' => '0061234573',
                'nama' => 'Galih Ramadhan',
                'kelas' => 'X IPA 2',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-04-02',
                'alamat' => 'Jl. Cempaka No. 12, Jakarta',
            ],
            [
                'nisn' => '0061234574',
                'nama' => 'Hana Pertiwi',
                'kelas' => 'X IPA 2',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-06-11',
                'alamat' => 'Jl. Flamboyan No. 8, Jakarta',
            ],
            [
                'nisn' => '0061234575',
                'nama' => 'Ilham Maulana',
                'kelas' => 'X IPA 2',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2009-12-25',
                'alamat' => 'Jl. Kamboja No. 14, Jakarta',
            ],
            [
                'nisn' => '0061234576',
                'nama' => 'Jihan Aulia',
                'kelas' => 'X IPA 2',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-08-17',
                'alamat' => 'Jl. Teratai No. 2, Jakarta',
            ],
        ];

        foreach ($data as $item) {
            $email = strtolower(explode(' ', $item['nama'])[0]).'@absensi.test';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $item['nama'],
                    'password' => 'password',
                    'role' => User::ROLE_SISWA,
                ],
            );

            Siswa::updateOrCreate(
                ['nisn' => $item['nisn']],
                [
                    'user_id' => $user->id,
                    'nama' => $item['nama'],
                    'kelas' => $item['kelas'],
                    'jenis_kelamin' => $item['jenis_kelamin'],
                    'tanggal_lahir' => $item['tanggal_lahir'],
                    'alamat' => $item['alamat'],
                ],
            );
        }
    }

    /**
     * @param  array<string, Guru>  $gurus
     * @param  array<string, Mapel>  $mapels
     * @return array<int, Jadwal>
     */
    private function seedJadwal(array $gurus, array $mapels): array
    {
        $data = [
            ['198501012010011001', 'MAT-01', 'X IPA 1', 'Senin', '07:00', '08:30'],
            ['198703152012012002', 'BIN-01', 'X IPA 1', 'Senin', '08:30', '10:00'],
            ['199205102017052004', 'BIG-01', 'X IPA 1', 'Selasa', '07:00', '08:30'],
            ['199001202015031003', 'KOM-01', 'X IPA 1', 'Selasa', '08:30', '10:00'],
            ['198501012010011001', 'IPA-01', 'X IPA 1', 'Rabu', '07:00', '08:30'],
            ['199205102017052004', 'PJK-01', 'X IPA 1', 'Rabu', '08:30', '10:00'],
            ['198501012010011001', 'MAT-01', 'X IPA 1', 'Kamis', '07:00', '08:30'],
            ['198703152012012002', 'BIN-01', 'X IPA 1', 'Jumat', '07:00', '08:30'],
            ['199001202015031003', 'KOM-01', 'X IPA 2', 'Senin', '07:00', '08:30'],
            ['199205102017052004', 'PJK-01', 'X IPA 2', 'Senin', '08:30', '10:00'],
            ['198501012010011001', 'MAT-01', 'X IPA 2', 'Selasa', '07:00', '08:30'],
            ['198703152012012002', 'BIN-01', 'X IPA 2', 'Selasa', '08:30', '10:00'],
            ['199205102017052004', 'BIG-01', 'X IPA 2', 'Rabu', '07:00', '08:30'],
            ['198501012010011001', 'IPA-01', 'X IPA 2', 'Rabu', '08:30', '10:00'],
            ['199205102017052004', 'PJK-01', 'X IPA 2', 'Kamis', '07:00', '08:30'],
            ['199001202015031003', 'KOM-01', 'X IPA 2', 'Jumat', '07:00', '08:30'],
        ];

        $jadwals = [];

        foreach ($data as [$nip, $kode, $kelas, $hari, $mulai, $selesai]) {
            $jadwals[] = Jadwal::updateOrCreate(
                [
                    'kelas' => $kelas,
                    'hari' => $hari,
                    'jam_mulai' => $mulai,
                ],
                [
                    'guru_id' => $gurus[$nip]->id,
                    'mapel_id' => $mapels[$kode]->id,
                    'jam_selesai' => $selesai,
                ],
            );
        }

        return $jadwals;
    }

    /**
     * Buat absensi untuk hari-hari sekolah yang sudah lewat (maksimal 14 hari terakhir).
     *
     * @param  array<int, Jadwal>  $jadwals
     */
    private function seedAbsensi(array $jadwals): void
    {
        $namaHari = [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
        ];

        $siswaPerKelas = Siswa::all()->groupBy('kelas');

        $statusHadir = [
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'sakit',
            'izin',
            'alfa',
        ];

        foreach ($jadwals as $jadwal) {
            $siswas = $siswaPerKelas->get($jadwal->kelas, collect());

            foreach (range(0, 13) as $mundur) {
                $tanggal = now()->subDays($mundur);

                if ($namaHari[$tanggal->dayOfWeek] !== $jadwal->hari) {
                    continue;
                }

                foreach ($siswas as $siswa) {
                    Absensi::firstOrCreate(
                        [
                            'jadwal_id' => $jadwal->id,
                            'siswa_id' => $siswa->id,
                            'tanggal' => $tanggal->toDateString(),
                        ],
                        [
                            'status' => fake()->randomElement($statusHadir),
                        ],
                    );
                }
            }
        }
    }
}

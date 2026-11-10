<?php

namespace Database\Factories;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jadwal_id' => Jadwal::factory(),
            'siswa_id' => Siswa::factory(),
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => fake()->randomElement(array_keys(Absensi::STATUS)),
            'keterangan' => null,
        ];
    }
}

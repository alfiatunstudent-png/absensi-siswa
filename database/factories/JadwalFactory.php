<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mapel_id' => Mapel::factory(),
            'guru_id' => Guru::factory(),
            'kelas' => 'X IPA 1',
            'hari' => fake()->randomElement(Jadwal::HARI),
            'jam_mulai' => fake()->randomElement(['07:00', '08:30', '10:15', '12:30']),
            'jam_selesai' => fake()->randomElement(['08:30', '10:00', '11:45', '14:00']),
        ];
    }
}

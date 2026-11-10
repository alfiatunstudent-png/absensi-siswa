<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nisn' => fake()->unique()->numerify('##########'),
            'nama' => fake()->name(),
            'kelas' => 'X IPA 1',
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tanggal_lahir' => fake()->dateTimeBetween('-17 years', '-15 years'),
            'alamat' => fake()->address(),
        ];
    }
}

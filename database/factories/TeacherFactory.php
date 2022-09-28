<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'student_name' => ['Mnr.', 'Mvr.'][array_rand(['Mnr.', 'Mvr.'])] . " " . $this->faker->lastName,
            'abbreviation' => strtoupper($this->faker->randomLetter . $this->faker->randomLetter . $this->faker->randomLetter)
        ];
    }
}

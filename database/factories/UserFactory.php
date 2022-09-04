<?php

namespace Database\Factories;

use App\Models\Role;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('test123test'),
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'sex' => ['m', 'v', 'o'][array_rand(['m', 'v', 'o'])],
            'phone_number' => $this->faker->phoneNumber,
            'date_of_birth' => $this->faker->dateTimeBetween('1990-01-01', '2021-12-31')->format('Y-m-d'),
            'country' => 'Nederland',
            'state' => 'Zuid-Holland',
            'city' => 'Rotterdam',
            'zipcode' => '1234AB',
            'street' => 'Teststraat 7',
            'role_id' => Role::whereName('Student')->firstOrFail(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

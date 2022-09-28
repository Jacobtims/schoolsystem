<?php

namespace Database\Factories;

use App\Models\Role;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    private Role $studentRole;
    private string $passwordHash;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);
        $this->studentRole = Role::whereName('Student')->firstOrFail();
        $this->passwordHash = Hash::make('test123test');
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->passwordHash,
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'sex' => ['Man', 'Vrouw', 'Overig'][array_rand(['Man', 'Vrouw', 'Overig'])],
            'phone_number' => $this->faker->phoneNumber,
            'date_of_birth' => $this->faker->dateTimeBetween('1990-01-01', '2021-12-31')->format('Y-m-d'),
            'country' => 'Nederland',
            'state' => $this->faker->state,
            'city' => $this->faker->city,
            'zipcode' => $this->faker->postcode,
            'street' => $this->faker->streetAddress,
            'role_id' => $this->studentRole->id,
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

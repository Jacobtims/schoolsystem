<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withPersonalTeam()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}

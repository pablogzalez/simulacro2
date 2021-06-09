<?php

namespace Database\Factories;

use App\User;
use App\UserProfile;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    public function configure()
    {
        return $this->afterCreating(function ($user) {
            $user->profile()->save(UserProfile::factory()->make());
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'role' => 'user',
            'remember_token' => Str::random(10),
            'active' => true,
        ];
    }

    public function inactive()
    {
        return $this->state(function ($faker) {
            return [
                'active' => false
            ];
        });
    }
}

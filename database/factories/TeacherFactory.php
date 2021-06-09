<?php

namespace Database\Factories;

use App\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->sentence(1),
            'last_name' => $this->faker->unique()->sentence(1),
            'dni' => $this->faker->unique()->uuid,
        ];
    }
}

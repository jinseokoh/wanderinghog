<?php

namespace Database\Factories;

use App\Models\Profile;
use Spatie\Enum\Faker\FakerEnumProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'profession_type' => $this->faker->numberBetween(1, 12),
            'height' => $this->faker->numberBetween(151, 191),
            'vehicle' => $this->faker->numberBetween(0, 2),
            'vegan' => $this->faker->randomDigit >= 9 ? 1 : 0,
            'smoking' => $this->faker->numberBetween(0, 1),
            'drinking' => $this->faker->numberBetween(0, 1),
            'latitude' => $this->faker->randomFloat(null, 36, 38),
            'longitude' => $this->faker->randomFloat(null, 127, 129),
            'intro' => $this->faker->sentence(),
            'coins' => 100,
        ];
    }
}

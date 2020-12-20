<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $gender = $this->faker->randomElement(['men', 'women']);
        $random = $this->faker->numberBetween(1, 99);
        $avatar = "https://randomuser.me/api/portraits/{$gender}/{$random}.jpg";

        return [
            'username' => $this->faker->unique()->word,
            'name' => $this->faker->name($gender === 'men' ? 'male' : 'female'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // password
            'gender' => $gender === 'men' ? 'M' : 'F',
            'avatar' => $avatar,
            'dob' => $this->faker->dateTimeBetween($startDate = '-40 years', $endDate = '-20 years')->format('Y-m-d'),
            'device' => $this->faker->randomElement(['iPhone 10', 'iPhone 11', 'iPhone 12', 'SAMSUNG', 'LG']),
            'uuid' => $this->faker->uuid,
            'email_verified_at' => now(),
            'is_active' => 1,
            'remember_token' => Str::random(10),
        ];
    }
}

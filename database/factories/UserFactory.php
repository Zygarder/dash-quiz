<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    protected $model = Dasher::class; // This line is REQUIRED
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->unique()->lastName();

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'active_status' => 0,
            'role' => 'dasher',
            'email' => strtolower($lastName) . '@test.com',
            'profile_photo' => null,
            'password' => Hash::make($lastName),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

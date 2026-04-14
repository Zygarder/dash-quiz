<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Dasher;

class DasherFactory extends Factory
{
    protected $model = Dasher::class;

    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->unique()->lastName();
        $email = fake()->unique()->email();

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'active_status' => 0,
            'role' => 'dasher',
            'email' => $email,
            'profile_photo' => null,
            'password' => Hash::make($lastName),
        ];
    }
}
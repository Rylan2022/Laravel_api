<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'title'   => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'user_id' => User::factory(), // links post to a user
        ];
    }
}

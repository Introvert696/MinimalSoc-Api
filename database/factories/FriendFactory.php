<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friend>
 */
class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "first_user" => User::get()->random()->id,
            "second_user" => User::get()->random()->id,
            'friend_status' => fake()->boolean()
        ];
    }
}

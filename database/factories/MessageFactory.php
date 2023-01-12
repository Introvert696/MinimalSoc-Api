<?php

namespace Database\Factories;

use App\Models\Messages_group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'from' => Messages_group::get()->random()->first_user,
            'to' => Messages_group::get()->random()->second_user,
            'message_group' => Messages_group::get()->random()->id,
            'content' => fake()->text(100)


        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Subscribe_to_group;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscribe_to_group>
 */
class Subscribe_to_groupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'group_id' => Group::get()->random()->id,
            'user_id' => User::get()->random()->id
        ];
    }
}

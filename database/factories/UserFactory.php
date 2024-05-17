<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'ivan.ivanov@test.org',
            'password' => '123456',
        ];
    }
}

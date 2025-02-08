<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),  // Generate a random name
            'description' => $this->faker->paragraph(),  // Random description
            'department_id' => \App\Models\Department::inRandomOrder()->first()->id,  // Random existing department
            'code' => strtoupper($this->faker->unique()->bothify('SUB###')),  // Random unique code like "SUB123"
        ];
    }
}

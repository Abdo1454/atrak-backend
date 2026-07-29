<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Oriental Perfumes',
                'French Perfumes',
                'Luxury Collection',
                'Men Perfumes',
                'Women Perfumes',
            ]),

            'slug' => fake()->unique()->slug(),

            'description' => fake()->sentence(),

            'image' => 'categories/default.jpg',
        ];
    }
}
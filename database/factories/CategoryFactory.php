<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        // Default static value supaya gak error kalau factory dipanggil
        return [
            'name' => 'Default Category',
        ];
    }
}

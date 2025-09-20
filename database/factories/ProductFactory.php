<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        // Default dummy data, tapi bisa diabaikan jika kamu selalu membuat data manual
        return [
            'name' => 'Default Product Name',
            'description' => 'Default product description',
            'price' => 100.00,
            'stock' => 10,
        ];
    }
}

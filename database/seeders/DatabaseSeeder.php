<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TableASeeder::class,
            TableBSeeder::class,
            TableCSeeder::class,
            TableDSeeder::class,
        ]);
    }
}

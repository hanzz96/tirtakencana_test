<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableDSeeder extends Seeder {
    public function run(): void {
        DB::table('table_d')->insert([
            ['kode_sales' => 'A1', 'nama_sales' => 'Alpha'],
            ['kode_sales' => 'A2', 'nama_sales' => 'Blue'],
            ['kode_sales' => 'A3', 'nama_sales' => 'Charlie'],
            ['kode_sales' => 'B1', 'nama_sales' => 'Delta'],
            ['kode_sales' => 'B2', 'nama_sales' => 'Echo'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableCSeeder extends Seeder {
    public function run(): void {
        DB::table('table_c')->insert([
            ['kode_toko' => 1, 'area_sales' => 'A'],
            ['kode_toko' => 2, 'area_sales' => 'A'],
            ['kode_toko' => 3, 'area_sales' => 'A'],
            ['kode_toko' => 4, 'area_sales' => 'B'],
            ['kode_toko' => 5, 'area_sales' => 'B'],
        ]);
    }
}

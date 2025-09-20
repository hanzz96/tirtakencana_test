<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableASeeder extends Seeder {
    public function run(): void {
        DB::table('table_a')->insert([
            ['kode_toko_baru' => 1, 'kode_toko_lama' => 6],
            ['kode_toko_baru' => 2, 'kode_toko_lama' => null],
            ['kode_toko_baru' => 3, 'kode_toko_lama' => 7],
            ['kode_toko_baru' => 4, 'kode_toko_lama' => 9],
            ['kode_toko_baru' => 5, 'kode_toko_lama' => 8],
        ]);
    }
}

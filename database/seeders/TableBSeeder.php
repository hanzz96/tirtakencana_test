<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableBSeeder extends Seeder {
    public function run(): void {
        DB::table('table_b')->insert([
            ['kode_toko' => 1, 'nominal_transaksi' => 1000.00],
            ['kode_toko' => 2, 'nominal_transaksi' => 1000.00],
            ['kode_toko' => 4, 'nominal_transaksi' => 1000.00],
            ['kode_toko' => 6, 'nominal_transaksi' => 1000.00],
            ['kode_toko' => 7, 'nominal_transaksi' => 1000.00],
        ]);
    }
}

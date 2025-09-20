<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('table_b', function (Blueprint $table) {
            $table->unsignedBigInteger('kode_toko')->primary();
            $table->decimal('nominal_transaksi', 8, 2);
        });
    }

    public function down(): void {
        Schema::dropIfExists('table_b');
    }
};

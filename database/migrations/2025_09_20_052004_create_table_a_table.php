<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('table_a', function (Blueprint $table) {
            $table->unsignedBigInteger('kode_toko_baru')->primary();
            $table->integer('kode_toko_lama')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('table_a');
    }
};

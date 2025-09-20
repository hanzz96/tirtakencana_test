<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('table_c', function (Blueprint $table) {
            $table->unsignedBigInteger('kode_toko')->primary();
            $table->string('area_sales', 10);
        });
    }

    public function down(): void {
        Schema::dropIfExists('table_c');
    }
};

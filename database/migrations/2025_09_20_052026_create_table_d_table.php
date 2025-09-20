<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('table_d', function (Blueprint $table) {
            $table->string('kode_sales')->primary();
            $table->string('nama_sales', 20);
        });
    }

    public function down(): void {
        Schema::dropIfExists('table_d');
    }
};

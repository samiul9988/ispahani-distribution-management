<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedInteger('pic')->default(1)->comment('Pieces per carton');
            $table->decimal('opening_stock_ctn', 14, 2)->default(0);
            $table->decimal('opening_stock_pcs', 14, 2)->default(0);
            $table->unsignedInteger('low_stock_threshold_ctn')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

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
        Schema::create('daily_sales', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date');
            $table->foreignId('sales_rep_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('loading_ctn', 12, 2)->default(0);
            $table->decimal('loading_pcs', 12, 2)->default(0);
            $table->decimal('total_loaded_pcs', 14, 2)->default(0);
            $table->decimal('unloading_pcs', 12, 2)->default(0);
            $table->decimal('sales_pcs', 14, 2)->default(0);
            $table->decimal('price_at_sale', 12, 2)->default(0);
            $table->decimal('total_taka', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('net_amount', 14, 2)->default(0);
            $table->timestamps();

            $table->unique(['sale_date', 'sales_rep_id', 'product_id'], 'daily_sales_unique_entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sales');
    }
};

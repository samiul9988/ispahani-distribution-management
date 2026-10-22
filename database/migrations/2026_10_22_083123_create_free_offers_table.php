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
        Schema::create('free_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['free_qty', 'fixed_discount']);
            $table->decimal('buy_qty', 12, 2)->comment('Pieces to buy to trigger the offer');
            $table->decimal('free_qty', 12, 2)->nullable()->comment('Free pieces given per trigger (free_qty type)');
            $table->decimal('discount_amount', 12, 2)->nullable()->comment('Flat discount Taka when triggered (fixed_discount type)');
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_offers');
    }
};

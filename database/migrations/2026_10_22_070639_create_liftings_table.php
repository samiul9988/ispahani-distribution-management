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
        Schema::create('liftings', function (Blueprint $table) {
            $table->id();
            $table->date('lifting_date');
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('lifting_ctn', 12, 2)->default(0);
            $table->decimal('lifting_pcs', 12, 2)->default(0);
            $table->decimal('total_lifted_pcs', 14, 2)->default(0);
            $table->decimal('value', 14, 2)->default(0)->comment('Purchase amount payable to Ispahani');
            $table->timestamps();

            $table->unique(['lifting_date', 'product_id'], 'liftings_unique_entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liftings');
    }
};

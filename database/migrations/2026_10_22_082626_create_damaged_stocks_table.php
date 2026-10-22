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
        Schema::create('damaged_stocks', function (Blueprint $table) {
            $table->id();
            $table->date('damage_date');
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('damage_sending_id')->nullable()->constrained('damage_sendings')->nullOnDelete();
            $table->decimal('qty_pcs', 12, 2);
            $table->decimal('value', 14, 2)->default(0);
            $table->string('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damaged_stocks');
    }
};

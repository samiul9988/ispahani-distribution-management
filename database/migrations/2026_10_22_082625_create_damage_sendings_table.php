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
        Schema::create('damage_sendings', function (Blueprint $table) {
            $table->id();
            $table->date('sending_date');
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_qty_pcs', 14, 2)->default(0);
            $table->decimal('total_value', 14, 2)->default(0);
            $table->string('status')->default('sent');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_sendings');
    }
};

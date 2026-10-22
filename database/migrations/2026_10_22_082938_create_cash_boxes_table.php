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
        Schema::create('cash_boxes', function (Blueprint $table) {
            $table->id();
            $table->date('cash_date')->unique();
            $table->decimal('opening_balance', 14, 2)->default(0);
            $table->decimal('total_collection', 14, 2)->default(0);
            $table->decimal('total_credit_collection', 14, 2)->default(0);
            $table->decimal('total_expense', 14, 2)->default(0);
            $table->decimal('total_supplier_payment', 14, 2)->default(0);
            $table->decimal('adjustment', 14, 2)->default(0)->comment('Manual correction for cash variance');
            $table->decimal('closing_balance', 14, 2)->default(0);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_boxes');
    }
};

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
        Schema::create('cash_credit_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->foreignId('sales_rep_id')->constrained()->cascadeOnDelete();
            $table->decimal('sales_amount', 14, 2)->default(0);
            $table->decimal('collection', 14, 2)->default(0);
            $table->decimal('credit', 14, 2)->default(0)->comment('Sales not collected today');
            $table->decimal('credit_collection', 14, 2)->default(0)->comment('Old-due collected today');
            $table->decimal('opening_credit_balance', 14, 2)->default(0);
            $table->decimal('route_credit', 14, 2)->default(0)->comment('Running credit balance after this day');
            $table->decimal('route_shortage', 14, 2)->default(0)->comment('Cash variance/shortfall found');
            $table->timestamps();

            $table->unique(['entry_date', 'sales_rep_id'], 'cash_credit_unique_entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_credit_entries');
    }
};

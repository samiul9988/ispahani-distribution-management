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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->date('po_date');
            $table->string('invoice_number')->nullable();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method')->nullable();
            $table->decimal('total_qty_pcs', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('payment_amount', 14, 2)->default(0);
            $table->string('status')->default('draft');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

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
        Schema::create('staff_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
            $table->date('salary_month');
            $table->decimal('amount', 12, 2);
            $table->date('paid_date')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique(['staff_id', 'salary_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_salaries');
    }
};

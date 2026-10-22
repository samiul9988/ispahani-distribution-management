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
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->date('attendance_date');
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['present', 'absent', 'leave', 'half_day'])->default('present');
            $table->timestamps();

            $table->unique(['attendance_date', 'staff_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
    }
};

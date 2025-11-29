<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates shifts table with reduction support
     */
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id('shift_id');
            $table->string('shift_code', 50)->unique();
            $table->string('shift_name', 255);
            $table->time('shift_start');
            $table->time('shift_end');
            $table->enum('shift_type', ['regular', 'reduce'])->default('regular');
            $table->decimal('reduction_percentage', 5, 2)->default(100.00);
            $table->date('effective_from_date')->nullable();
            $table->integer('half_day_threshold_minutes')->default(240); // 4 hours
            $table->integer('absent_threshold_minutes')->default(120); // 2 hours
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('shift_code');
            $table->index('shift_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};

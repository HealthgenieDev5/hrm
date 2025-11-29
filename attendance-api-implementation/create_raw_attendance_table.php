<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates raw_attendance table for eTime Office punch data
     */
    public function up(): void
    {
        Schema::create('raw_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('Empcode', 50);
            $table->time('INTime')->nullable();
            $table->time('OUTTime')->nullable();
            $table->string('DateString', 20)->nullable(); // DD/MM/YYYY format from eTime
            $table->date('DateString_2'); // YYYY-MM-DD format for queries
            $table->string('Remark', 255)->nullable();
            $table->string('machine', 20)->nullable(); // del, ggn, hn, skbd
            $table->string('default_machine', 20)->nullable();
            $table->string('override_machine', 20)->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate punches
            $table->unique(['Empcode', 'DateString_2'], 'emp_date_unique');

            $table->index('Empcode');
            $table->index('DateString_2');
            $table->index('machine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_attendance');
    }
};

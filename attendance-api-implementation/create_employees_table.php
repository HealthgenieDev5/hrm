<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates employees table with minimal fields needed for attendance processing
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('name', 255);
            $table->string('emp_code', 50)->unique()->nullable();
            $table->date('joining_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'exit'])->default('active');
            $table->timestamps();

            $table->index('emp_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('designation');
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('shift_start')->nullable();
            $table->string('shift_end')->nullable();
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->date('dob')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

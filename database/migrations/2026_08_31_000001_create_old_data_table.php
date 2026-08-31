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
        Schema::create('old_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();
            $table->string('sn')->nullable();
            $table->string('staff_name')->nullable();
            $table->string('entry_date')->nullable();
            $table->string('entry_time')->nullable();
            $table->text('work_name')->nullable();
            $table->text('units')->nullable();
            $table->text('description')->nullable();
            $table->text('location')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_data');
    }
};

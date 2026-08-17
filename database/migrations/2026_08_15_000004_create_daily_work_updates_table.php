<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_work_updates', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->date('date');
            $table->text('report');
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_work_updates');
    }
};

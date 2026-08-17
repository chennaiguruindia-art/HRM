<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->string('salesperson')->nullable();
            $table->text('company_address')->nullable();
            $table->text('company_details')->nullable();
            $table->text('purpose_of_visit')->nullable();
            $table->text('type_of_service')->nullable();
            $table->text('inspection')->nullable();
            $table->text('quotation')->nullable();
            $table->text('followup1')->nullable();
            $table->text('followup2')->nullable();
            $table->text('followup3')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_plans');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_calculations', function (Blueprint $table) {
            $table->integer('leave_days')->default(0)->after('absent_days');
            $table->integer('paid_leaves_used')->default(0)->after('leave_days');
            $table->integer('deductible_days')->default(0)->after('paid_leaves_used');
        });
    }

    public function down(): void
    {
        Schema::table('salary_calculations', function (Blueprint $table) {
            $table->dropColumn(['leave_days', 'paid_leaves_used', 'deductible_days']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->string('employee_id', 20)->nullable()->after('user_id');
            $table->foreignId('user_id')->nullable()->change();
            $table->index('employee_id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->string('employee_id', 20)->nullable()->after('user_id');
            $table->foreignId('user_id')->nullable()->change();
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
            $table->dropColumn('employee_id');
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
            $table->dropColumn('employee_id');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};

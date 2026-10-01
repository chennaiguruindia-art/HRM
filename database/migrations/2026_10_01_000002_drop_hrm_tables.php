<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'notifications',
        'leave_requests',
        'salary_calculations',
        'daily_work_updates',
        'attendances',
        'employees',
        'designations',
        'holidays',
        'admin_notifications',
        'old_data',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
            }
        }
    }

    public function down(): void
    {
        //
    }
};

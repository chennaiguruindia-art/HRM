<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('mobile', 20)->nullable()->after('photo');
            $table->string('emergency_contact', 20)->nullable()->after('mobile');
            $table->string('state', 100)->nullable()->after('emergency_contact');
            $table->string('city', 100)->nullable()->after('state');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'emergency_contact', 'state', 'city']);
        });
    }
};

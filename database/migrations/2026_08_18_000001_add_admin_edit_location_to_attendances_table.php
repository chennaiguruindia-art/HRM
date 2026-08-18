<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('edited_lat', 10, 7)->nullable()->after('location_name');
            $table->decimal('edited_lng', 10, 7)->nullable()->after('edited_lat');
            $table->string('edited_location_name', 255)->nullable()->after('edited_lng');
            $table->unsignedBigInteger('edited_by')->nullable()->after('edited_location_name');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['edited_lat', 'edited_lng', 'edited_location_name', 'edited_by']);
        });
    }
};

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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['loggable_type', 'loggable_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->nullableMorphs('loggable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['loggable_type', 'loggable_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->morphs('loggable');
        });
    }
};

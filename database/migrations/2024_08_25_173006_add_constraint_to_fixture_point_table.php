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
        Schema::table('fixture_point', function (Blueprint $table) {
            $table->unique(['fixture_id', 'team_id', 'points_rule_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixture_point', function (Blueprint $table) {
            $table->dropUnique(['fixture_id', 'team_id', 'points_rule_id']);
        });
    }
};

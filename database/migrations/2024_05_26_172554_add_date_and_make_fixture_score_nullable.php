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
        Schema::table('fixtures', function (Blueprint $table) {
            $table->unsignedInteger('league_id')->after('api_id');
            $table->unsignedInteger('home_team_score')->change()->nullable();
            $table->unsignedInteger('away_team_score')->change()->nullable();
            $table->dateTime('date')->nullable()->after('away_team_score');
        });
    }
};

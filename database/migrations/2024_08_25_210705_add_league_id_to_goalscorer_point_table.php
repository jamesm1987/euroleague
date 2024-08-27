<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\League;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('goalscorer_point', function (Blueprint $table) {
            $table->foreignIdFor(League::class)->after('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goalscorer_point', function (Blueprint $table) {
            $table->dropColumn('league_id');
        });
    }
};

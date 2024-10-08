<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Fixture;
use App\Models\Team;
use App\Models\PointsRule;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fixture_point', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Fixture::class)->onDelete('cascade');
            $table->foreignIdFor(Team::class)->onDelete('cascade');
            $table->foreignIdFor(PointsRule::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixture_point');
    }
};

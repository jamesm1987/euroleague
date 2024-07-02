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
        Schema::create('points_rules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('description');
            $table->string('outcome');
            $table->string('condition')->nullable();
            $table->tinyInteger('threshold')->nullable();
            $table->tinyInteger('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_rules');
    }
};

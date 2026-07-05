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
        Schema::create('standings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tournament_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('club_id')
                ->constrained()
                ->onDelete('cascade');

            $table->unsignedInteger('played')->default(0);

            $table->unsignedInteger('win')->default(0);

            $table->unsignedInteger('draw')->default(0);

            $table->unsignedInteger('lose')->default(0);

            $table->unsignedInteger('goals_for')->default(0);

            $table->unsignedInteger('goals_against')->default(0);

            $table->integer('goal_difference')->default(0);

            $table->unsignedInteger('points')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};

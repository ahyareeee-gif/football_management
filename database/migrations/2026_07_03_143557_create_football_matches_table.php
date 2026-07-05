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
        Schema::create('football_matches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tournament_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('home_club_id')
                ->constrained('clubs')
                ->onDelete('cascade');

            $table->foreignId('away_club_id')
                ->constrained('clubs')
                ->onDelete('cascade');

            $table->dateTime('match_date');

            $table->string('venue')->nullable();

            $table->enum('status', [
                'Scheduled',
                'Finished',
                'Postponed'
            ])->default('Scheduled');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('football_matches');
    }
};

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
        Schema::create('players', function (Blueprint $table) {
            $table->id();

            $table->foreignId('club_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('name');

            $table->enum('position', [
                'Goalkeeper',
                'Defender',
                'Midfielder',
                'Forward'
            ]);

        $table->integer('jersey_number');

        $table->date('birth_date')->nullable();

        $table->string('photo')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

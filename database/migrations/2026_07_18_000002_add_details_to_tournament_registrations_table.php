<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournament_registrations', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->after('status');
            $table->string('contact_phone')->nullable()->after('contact_person');
            $table->text('notes')->nullable()->after('contact_phone');
            $table->string('registration_document')->nullable()->after('payment_proof');
            $table->boolean('agreement_accepted')->default(false)->after('registration_document');
        });
    }

    public function down(): void
    {
        Schema::table('tournament_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person',
                'contact_phone',
                'notes',
                'registration_document',
                'agreement_accepted',
            ]);
        });
    }
};
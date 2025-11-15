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
        Schema::table('mouvements_stock', function (Blueprint $table) {
            $table->string('nom_client')->nullable()->after('notes');
            $table->string('telephone_client')->nullable()->after('nom_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mouvements_stock', function (Blueprint $table) {
            $table->dropColumn(['nom_client', 'telephone_client']);
        });
    }
};

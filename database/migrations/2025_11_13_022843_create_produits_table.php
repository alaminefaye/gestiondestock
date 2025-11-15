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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('reference')->unique();
            $table->text('description')->nullable();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->onDelete('set null');
            $table->decimal('prix_achat', 10, 2)->default(0);
            $table->decimal('prix_vente', 10, 2)->default(0);
            $table->integer('quantite_stock')->default(0);
            $table->integer('seuil_alerte')->default(10);
            $table->string('unite')->default('unité'); // unité, kg, litre, etc.
            $table->string('image')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};

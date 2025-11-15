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
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->enum('type', ['entree', 'sortie']);
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2)->nullable();
            $table->string('motif')->nullable(); // Achat, Vente, Ajustement, Perte, etc.
            $table->text('notes')->nullable();
            $table->date('date_mouvement');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};

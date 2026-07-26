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
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');

            // Fréquence de facturation (mensuel, trimestriel, annuel)
            $table->enum('frequence_facturation', ['mensuel', 'trimestriel', 'annuel'])->default('mensuel');

            // Prix du tarif en FCFA (Decimal pour la précision financière)
            $table->decimal('prix', 12, 2);

            // Période d'essai en jours (par défaut 0)
            $table->integer('duree_jours')->default(0);

            // Limites (0 = illimité)
            $table->integer('max_agences')->default(0);
            $table->integer('max_utilisateurs')->default(0);

            // Description / Avantages inclus
            $table->text('description')->nullable();

            // Statut de disponibilité
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};

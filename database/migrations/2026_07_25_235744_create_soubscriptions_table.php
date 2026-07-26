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
        Schema::create('soubscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('agence_id');
            $table->integer('tarif_id');
            $table->string('date_debut');
            $table->string('date_fin');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soubscriptions');
    }
};

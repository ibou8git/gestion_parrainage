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
        Schema::create('parrainages', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('electeur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignUuid('candidat_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->date('date_parrainage');
            $table->enum('statut', ['EN_ATTENTE', 'VALIDE', 'REJETE'])->default('EN_ATTENTE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parrainages');
    }
};

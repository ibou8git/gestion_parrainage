<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('electeurs_erreurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_id'); // Clé étrangère vers `historique_uploads`
            $table->string('carte_identite', 255); // Numéro de carte d'identité
            $table->string('num_electeur', 255); // Numéro d'électeur
            $table->text('erreur'); // Message d'erreur
            $table->timestamps(); // Colonnes `created_at` et `updated_at`

            // Clé étrangère vers la table `historique_uploads`
            $table->foreign('upload_id')
                  ->references('id')
                  ->on('historique_uploads')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('electeurs_erreurs');
    }
};
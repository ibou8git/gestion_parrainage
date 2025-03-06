<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Créer la table `historique_uploads` en premier
        Schema::create('historique_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ip');
            $table->string('cle_utilisee');
            $table->timestamp('date_upload');
            $table->boolean('reussi')->default(false);
            $table->timestamps();
        });

        // 2. Créer la table `electeurs_erreurs` avec la clé étrangère
        Schema::create('electeurs_erreurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained('historique_uploads')->onDelete('cascade');
            $table->string('carte_identite');
            $table->string('num_electeur');
            $table->text('erreur');
            $table->timestamps();
        });

        // 3. Créer la table `temp_electeurs` en dernier
        Schema::create('temp_electeurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('carte_identite')->unique();
            $table->string('num_electeur')->unique();
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->string('bureau_vote');
            $table->boolean('valide')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        // Supprimer les tables dans l'ordre inverse de leur création
        Schema::dropIfExists('temp_electeurs');
        Schema::dropIfExists('electeurs_erreurs');
        Schema::dropIfExists('historique_uploads');
    }
};

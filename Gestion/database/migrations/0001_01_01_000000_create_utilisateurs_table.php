<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilisateursTable extends Migration
{
    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('email', 150)->unique();
            $table->string('mot_de_passe', 255);
            $table->string('nom_utilisateur', 100)->unique();
            $table->enum('type_utilisateur', ['ADMINISTRATEUR', 'ELECTEUR', 'CANDIDAT']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
}

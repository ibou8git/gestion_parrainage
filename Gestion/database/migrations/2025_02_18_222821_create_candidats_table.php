<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidats', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // Changement ici
            $table->string('numero_carte', 20)->unique();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->date('date_naissance');
            $table->string('email', 255)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('parti_politique', 100)->nullable();
            $table->string('slogan', 255)->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('couleur_1', 7)->nullable();
            $table->string('couleur_2', 7)->nullable();
            $table->string('couleur_3', 7)->nullable();
            $table->string('url_infos', 255)->nullable();
            $table->string('code_securite', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};

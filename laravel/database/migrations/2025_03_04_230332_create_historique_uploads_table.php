<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('historique_uploads', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('ip');
        $table->string('cle_utilisee');
        $table->boolean('reussi')->default(false);
        $table->string('etat')->nullable(); // Colonne pour l'état
        $table->timestamps();
    });

    // Définir l'encodage de la table
    DB::statement('ALTER TABLE historique_uploads CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
}

    public function down()
    {
        Schema::dropIfExists('historique_uploads');
    }
};
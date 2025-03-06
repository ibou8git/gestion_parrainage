<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historique_uploads', function (Blueprint $table) {
            $table->id(); // Colonne `id` de type bigint unsigned
            $table->unsignedBigInteger('user_id'); // ID de l'utilisateur
            $table->string('ip'); // Adresse IP
            $table->string('cle_utilisee'); // Clé utilisée pour l'upload
            $table->boolean('reussi')->default(false); // Succès ou échec de l'upload
            $table->timestamps(); // Colonnes `created_at` et `updated_at`
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_uploads');
    }
};
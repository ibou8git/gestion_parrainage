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
            $table->unsignedBigInteger('upload_id')->nullable(); // Permettre NULL
            $table->string('carte_identite');
            $table->string('num_electeur');
            $table->text('erreur');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('electeurs_erreurs');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_electeurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('carte_identite', 12);
            $table->string('num_electeur', 10); // Supprimer ->unique()
            $table->string('email');
            $table->string('telephone');
            $table->string('bureau_vote');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_electeurs');
    }
};

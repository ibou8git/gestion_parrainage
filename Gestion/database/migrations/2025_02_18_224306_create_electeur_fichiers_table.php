<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElecteurFichiersTable extends Migration
{
    public function up()
    {
        Schema::create('electeur_fichier', function (Blueprint $table) {
            $table->unsignedBigInteger('fichier_id');
            $table->string('electeur_id', 36);
            $table->primary(['fichier_id', 'electeur_id']);

            $table->foreign('fichier_id')
                ->references('id')
                ->on('fichier_electos')
                ->onDelete('cascade');

            $table->foreign('electeur_id')
                ->references('id')
                ->on('electeurs')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('electeur_fichier');
    }
}

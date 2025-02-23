<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParrainagesTable extends Migration
{
    public function up()
    {
        Schema::create('parrainages', function (Blueprint $table) {
            $table->id();
            $table->date('date_parrainage');
            $table->unsignedBigInteger('electeur_id'); // Changement ici
            $table->unsignedBigInteger('candidat_id'); // Changement ici
            $table->enum('statut', ['EN_ATTENTE', 'VALIDE', 'REJETE'])->default('EN_ATTENTE');
            $table->timestamps();

            $table->foreign('electeur_id')
                ->references('id')
                ->on('electeurs')
                ->onDelete('cascade');

            $table->foreign('candidat_id')
                ->references('id')
                ->on('candidats')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('parrainages');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatsTable extends Migration
{
    public function up()
    {
        Schema::create('candidats', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('slogan', 255)->nullable();
            $table->string('couleur_parti', 50)->nullable();
            $table->string('photo', 255)->nullable();
            $table->timestamps();

            $table->foreign('id')
                ->references('id')
                ->on('utilisateurs')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidats');
    }
}

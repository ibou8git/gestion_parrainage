<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElecteursTable extends Migration
{
    public function up()
    {
        Schema::create('electeurs', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('num_carte_e', 20)->unique();
            $table->string('bureau_vote', 100);
            $table->string('num_carte_identite', 20)->unique();
            $table->timestamps();

            $table->foreign('id')
                ->references('id')
                ->on('utilisateurs')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('electeurs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichierElectosTable extends Migration
{
    public function up()
    {
        Schema::create('fichier_electos', function (Blueprint $table) {
            $table->id();
            $table->string('checksum', 255)->unique();
            $table->boolean('etat_upload')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fichier_electos');
    }
}

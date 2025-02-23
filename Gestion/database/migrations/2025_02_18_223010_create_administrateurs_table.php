<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrateursTable extends Migration
{
    public function up()
    {
        Schema::create('administrateurs', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->enum('role', ['SUPER_ADMIN', 'VERIFICATEUR']);
            $table->timestamps();

            $table->foreign('id')
                ->references('id')
                ->on('utilisateurs')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrateurs');
    }
}

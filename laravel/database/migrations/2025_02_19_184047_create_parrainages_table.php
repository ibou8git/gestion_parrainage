<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parrainages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('electeur_id')->constrained('electeurs')->onDelete('cascade');
            $table->foreignId('candidat_id')->constrained('candidats')->onDelete('cascade');
            $table->string('code_validation')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parrainages');
    }
};

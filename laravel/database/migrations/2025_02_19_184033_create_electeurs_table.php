<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('electeurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('carte_identite', 12)->unique();
            $table->string('num_electeur', 10)->unique();
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->string('bureau_vote');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('electeurs');
    }
};

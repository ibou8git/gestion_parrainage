<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historique_uploads', function (Blueprint $table) {
            $table->string('etat')->nullable()->after('reussi'); // Add the 'etat' column
        });
    }
    
    public function down()
    {
        Schema::table('historique_uploads', function (Blueprint $table) {
            $table->dropColumn('etat'); // Remove the 'etat' column if the migration is rolled back
        });
    }
};

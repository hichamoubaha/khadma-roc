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
    Schema::create('candidatures', function (Blueprint $table) {
        $table->id();
        $table->text('message')->nullable();
        $table->string('cv')->nullable();
        $table->enum('statut', ['En attente', 'Acceptée', 'Refusée'])->default('En attente');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('annonce_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidatures');
    }
};

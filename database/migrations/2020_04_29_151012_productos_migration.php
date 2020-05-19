<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductosMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre'); 
            $table->float('precio');  
            $table->enum('tipo',['racion','tapa','entrante','postre','bebida']);
            $table->enum('especialidad',['carne','pescado','vegetariano','alcohol','sinalcohol','postre']);
            $table->integer('id_adm')->unsigned();
            $table->foreign('id_adm')->references('id')->on('administradores')->onDelete('cascade');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}

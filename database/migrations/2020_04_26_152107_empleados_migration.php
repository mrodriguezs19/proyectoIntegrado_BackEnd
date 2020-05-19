<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmpleadosMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dni');
            $table->string('nombre_completo');
            $table->string('correo');
            $table->string('contrasena');
            $table->integer('sueldo');
            $table->enum('puesto',['camarero','cocinero']);
            $table->integer('id_adm')->unsigned();

            // Indicamos cual es la clave foránea de esta tabla:
            $table->foreign('id_adm')->references('id')->on('administradores')->onDelete('cascade');;
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
        Schema::dropIfExists('empleados');
    }
}

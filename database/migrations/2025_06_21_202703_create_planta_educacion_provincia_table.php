<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantaEducacionProvinciaTable extends Migration
{
    public function up()
    {
        Schema::create('planta_educacion_provincia', function (Blueprint $table) {
            $table->id('id_cargo');
            $table->string('legajo');
            $table->string('nombre');
            $table->string('dni');
            $table->string('fecha_ingreso');
            $table->string('dependencia');
            $table->string('dependencia_comp');
            $table->string('escalafon');
            $table->string('agrupamiento');
            $table->string('subrogancia');
            $table->string('cargo');
            $table->string('nro_cargo');
            $table->string('caracter');
            $table->string('dedicacion');
            $table->string('alta_cargo');
            $table->date('vencimiento_cargo')->nullable();
            $table->integer('hs');
            $table->string('licencia');
            $table->string('desempenio');
            $table->string('estado_baja');
            $table->integer('puntaje');
            $table->date('alta_licencia')->nullable();
            $table->date('baja_licencia')->nullable();
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('planta_educacion_provincia');
    }
}
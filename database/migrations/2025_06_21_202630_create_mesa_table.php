<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_mesa_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMesaTable extends Migration
{
    public function up()
    {
        Schema::create('mesa', function (Blueprint $table) {
            $table->id();
            $table->string('usuario');
            $table->string('entrada');
            $table->string('nombre');
            $table->string('dependencia');
            $table->string('entregado_a');
            $table->string('estado');
            $table->date('fecha');
            $table->timestamps(0); // Añadir timestamps si quieres gestionarlos
        });
    }

    public function down()
    {
        Schema::dropIfExists('mesa');
    }
}
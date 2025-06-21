<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID del usuario, llave primaria
            $table->string('name'); // Nombre del usuario
            $table->string('email')->unique(); // Correo único
            $table->string('password'); // Contraseña
            $table->string('cuil')->unique(); // CUIT o CUIL único
            $table->string('dependencia'); // Dependencia del usuario
            $table->string('desempenio'); // Desempeño del usuario
            $table->string('permiso'); // Permiso o rol del usuario
            $table->rememberToken(); // Token de "remember me" para autenticación
            $table->timestamps(); // Campos created_at y updated_at, si los necesitas
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
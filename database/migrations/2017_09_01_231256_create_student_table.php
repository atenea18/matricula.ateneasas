<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('username')->nullable();
            $table->string('password', 60)->nullable();
            $table->string('remember_token', 100)->unique();
            $table->string('picture', 60)->nullable();

            // Relacion con el estado del estudiante
            $table->unsignedInteger('state_id');
            $table->foreign('state_id')
                  ->references('id')->on('student_states')
                  ->onDelete('cascade');

            // Relacion identificación
            $table->unsignedBigInteger('identification_id');
            $table->foreign('identification_id')
                  ->references('id')->on('identification')
                  ->onDelete('cascade');

            // Relacion Direccion
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')
                  ->references('id')->on('address')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('student');
    }
}

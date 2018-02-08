<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_assignment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');

            // Relacion con la matricula
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->foreign('enrollment_id')
                  ->references('id')->on('enrollment')
                  ->onDelete('cascade');

            // Relacion con el grupo
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')
                  ->references('id')->on('group')
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
        Schema::dropIfExists('group_assignment');
    }
}

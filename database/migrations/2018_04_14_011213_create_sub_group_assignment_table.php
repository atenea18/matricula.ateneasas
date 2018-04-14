<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubGroupAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_group_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relacion con la matricula
            $table->unsignedBigInteger('enrollment_id');
            $table->foreign('enrollment_id')
                  ->references('id')->on('enrollment')
                  ->onDelete('cascade');

            // Relacion con el subgrupo
            $table->unsignedBigInteger('subgroup_id');
            $table->foreign('subgroup_id')
                  ->references('id')->on('sub_group')
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
        Schema::dropIfExists('sub_group_assignments');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');

            // Relación Grado
            $table->unsignedInteger('grade_id');
            $table->foreign('grade_id')
                ->references('id')->on('grade');

            $table->unsignedBigInteger('headquarter_id');
            $table->foreign('headquarter_id')
                ->references('id')->on('headquarter');



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
        Schema::dropIfExists('sub_group');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPensumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_pensum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_group_pensum', 191)->unique();
            $table->integer('percent')->nullable();
            $table->text('description')->nullable();
            $table->integer('ihs')->nullable();
            $table->integer('order')->nullable();

            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')
                ->references('id')->on('group');

            $table->unsignedBigInteger('asignatures_id');
            $table->foreign('asignatures_id')
                ->references('id')->on('asignatures');

            $table->unsignedBigInteger('areas_id');
            $table->foreign('areas_id')
                ->references('id')->on('areas');

            $table->unsignedBigInteger('subjects_type_id');
            $table->foreign('subjects_type_id')
                ->references('id')->on('subjects_type');

            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')
                ->references('id')->on('teachers');

            $table->unsignedBigInteger('schoolyear_id');
            $table->foreign('schoolyear_id')
                ->references('id')->on('schoolyears');



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
        Schema::dropIfExists('group_pensum');
    }
}

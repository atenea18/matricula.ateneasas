<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('quota')->nullable();
            // $table->enum('modality', ['t', 'a'])->default('a');
            // $table->enum('type', ['group', 'sub_group'])->default('group');

            // Relación Sede
            $table->unsignedBigInteger('headquarter_id');
            $table->foreign('headquarter_id')
                  ->references('id')->on('headquarter')
                  ->onDelete('cascade');

            // Relación Grado
            $table->unsignedInteger('grade_id');
            $table->foreign('grade_id')
                  ->references('id')->on('grade')
                  ->onDelete('cascade');

            // Relación Jornada
            $table->unsignedInteger('working_day_id');
            $table->foreign('working_day_id')
                  ->references('id')->on('working_day')
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
        Schema::dropIfExists('group');
    }
}

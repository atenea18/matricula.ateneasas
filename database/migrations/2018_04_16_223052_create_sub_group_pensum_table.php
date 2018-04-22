<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubGroupPensumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_group_pensum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();
            $table->integer('percent')->nullable();
            $table->text('description')->nullable();
            $table->integer('ihs')->nullable();
            $table->integer('order')->nullable();

            $table->unsignedBigInteger('sub_group_id');
            $table->foreign('sub_group_id')
                ->references('id')->on('sub_group');

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

        Schema::table('sub_group_pensum', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_sub_group_pensum;
                CREATE TRIGGER insert_code_sub_group_pensum BEFORE INSERT ON `sub_group_pensum` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.sub_group_id,"-",NEW.asignatures_id,NEW.schoolyear_id);
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_sub_group_pensum;
                CREATE TRIGGER update_code_sub_group_pensum BEFORE UPDATE ON `sub_group_pensum` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.sub_group_id,"-",NEW.asignatures_id,NEW.schoolyear_id);
                END;                
            ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_group_pensum');
    }
}

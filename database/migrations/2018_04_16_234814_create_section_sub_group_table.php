<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionSubGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_sub_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();

            $table->unsignedInteger('section_id');
            $table->foreign('section_id')
                ->references('id')->on('section');

            $table->unsignedBigInteger('sub_group_id');
            $table->foreign('sub_group_id')
                ->references('id')->on('sub_group');


            $table->timestamps();
        });

        Schema::table('section_sub_group', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_section_sub_group;
                CREATE TRIGGER insert_code_section_sub_group BEFORE INSERT ON `section_sub_group` FOR EACH ROW
                BEGIN
                SET NEW.code = NEW.sub_group_id;
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_section_sub_group;
                CREATE TRIGGER update_code_section_sub_group BEFORE UPDATE ON `section_sub_group` FOR EACH ROW
                BEGIN
                SET NEW.code = NEW.sub_group_id;
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
        Schema::dropIfExists('section_sub_group');
    }
}

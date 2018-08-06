<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPensumPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_pensum_performances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();

            $table->unsignedBigInteger('performances_id');
            $table->foreign('performances_id')
                ->references('id')->on('performances');

            $table->unsignedBigInteger('group_pensum_id');
            $table->foreign('group_pensum_id')
                ->references('id')->on('group_pensum');

            $table->unsignedInteger('periods_id');
            $table->foreign('periods_id')
                ->references('id')->on('periods');

            $table->timestamps();
        });

        Schema::table('group_pensum_performances', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_gropenper;
                CREATE TRIGGER insert_code_gropenper BEFORE INSERT ON `group_pensum_performances` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.group_pensum_id,"-",NEW.performances_id,"-",NEW.periods_id);
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_gropenper;
                CREATE TRIGGER update_code_gropenper BEFORE UPDATE ON `group_pensum_performances` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.group_pensum_id,"-",NEW.performances_id,"-",NEW.periods_id);
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
        Schema::dropIfExists('group_pensum_performances');
    }
}

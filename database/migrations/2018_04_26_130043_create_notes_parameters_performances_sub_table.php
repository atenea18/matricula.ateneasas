<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesParametersPerformancesSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_parameters_performances_sub', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();

            $table->unsignedBigInteger('notes_parameters_id');
            $table->foreign('notes_parameters_id')
                ->references('id')->on('notes_parameters');

            $table->unsignedBigInteger('performances_id');
            $table->foreign('performances_id')
                ->references('id')->on('performances');

            $table->unsignedBigInteger('sub_group_pensum_id');
            $table->foreign('sub_group_pensum_id')
                ->references('id')->on('sub_group_pensum');

            $table->unsignedInteger('periods_id');
            $table->foreign('periods_id')
                ->references('id')->on('periods');
            $table->timestamps();
        });

        Schema::table('notes_parameters_performances_sub', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_codenpp_sub;
                CREATE TRIGGER insert_codenpp_sub BEFORE INSERT ON `notes_parameters_performances_sub` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.sub_group_pensum_id,"-",NEW.periods_id,"-",NEW.notes_parameters_id);
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_codenpp_sub;
                CREATE TRIGGER update_codenpp_sub BEFORE UPDATE ON `notes_parameters_performances_sub` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.sub_group_pensum_id,"-",NEW.periods_id,"-",NEW.notes_parameters_id);
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
        Schema::dropIfExists('notes_parameters_performances_sub');
    }
}

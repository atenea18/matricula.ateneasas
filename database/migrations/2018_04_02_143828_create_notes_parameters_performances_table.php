<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesParametersPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_parameters_performances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();

            $table->unsignedBigInteger('notes_parameters_id');
            $table->foreign('notes_parameters_id')
                ->references('id')->on('notes_parameters');

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

        Schema::table('notes_parameters_performances', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_codenpp;
                CREATE TRIGGER insert_codenpp BEFORE INSERT ON `notes_parameters_performances` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.notes_parameters_id,NEW.group_pensum_id,NEW.periods_id);
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_codenpp;
                CREATE TRIGGER update_codenpp BEFORE UPDATE ON `notes_parameters_performances` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.notes_parameters_id,NEW.group_pensum_id,NEW.periods_id);
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
        Schema::table('notes_parameters_performances', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_codenpp`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_codenpp`');
        });

        Schema::dropIfExists('notes_performances');
    }
}

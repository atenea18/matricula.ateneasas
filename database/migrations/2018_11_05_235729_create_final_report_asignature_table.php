<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalReportAsignatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_report_asignature', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();
            $table->double('value');
            $table->double('overcoming');

            $table->unsignedBigInteger('enrollment_id');
            $table->foreign('enrollment_id')
                ->references('id')->on('enrollment');

            $table->unsignedBigInteger('asignatures_id');
            $table->foreign('asignatures_id')
                ->references('id')->on('asignatures');

            $table->timestamps();
        });

        Schema::table('final_report_asignature', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_q;
                CREATE TRIGGER insert_code_q BEFORE INSERT ON `final_report_asignature` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.enrollment_id,"-",NEW.asignatures_id);
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_q;
                CREATE TRIGGER update_code_q BEFORE UPDATE ON `final_report_asignature` FOR EACH ROW
                BEGIN
                SET NEW.code = CONCAT(NEW.enrollment_id,"-",NEW.asignatures_id);
                END;                
            ');
        });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('final_report_asignature');
    }
}

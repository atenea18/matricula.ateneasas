<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();
            $table->double('average');
            $table->double('overcoming')->nullable();
            $table->string('keep_going');
            $table->text('description');

            $table->unsignedBigInteger('enrollment_id');
            $table->foreign('enrollment_id')
                ->references('id')->on('enrollment');

            $table->unsignedBigInteger('news_id');
            $table->foreign('news_id')
                ->references('id')->on('news');

            $table->timestamps();
        });

        Schema::table('final_report', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code_x;
                CREATE TRIGGER insert_code_x BEFORE INSERT ON `final_report` FOR EACH ROW
                BEGIN
                SET NEW.code = NEW.enrollment_id;
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code_x;
                CREATE TRIGGER update_code_x BEFORE UPDATE ON `final_report` FOR EACH ROW
                BEGIN
                SET NEW.code = NEW.enrollment_id;
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
        Schema::dropIfExists('final_report');
    }
}

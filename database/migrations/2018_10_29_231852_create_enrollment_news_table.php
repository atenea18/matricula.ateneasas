<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollment_news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();

            $table->date('date')->nullable();

            $table->unsignedBigInteger('enrollment_id');
            $table->foreign('enrollment_id')
                ->references('id')->on('enrollment');

            $table->unsignedBigInteger('news_id');
            $table->foreign('news_id')
                ->references('id')->on('news');

            $table->timestamps();
        });

        Schema::table('enrollment_news', function (Blueprint $table) {
            //Disparadores
            \Illuminate\Support\Facades\DB::unprepared('
                DROP PROCEDURE IF EXISTS insert_code;
                CREATE TRIGGER insert_code BEFORE INSERT ON `enrollment_news` FOR EACH ROW
                BEGIN
                SET NEW.code = NEW.enrollment_id;
                END;
                
            ');
            \Illuminate\Support\Facades\DB::unprepared('
              DROP PROCEDURE IF EXISTS update_code;
                CREATE TRIGGER update_code BEFORE UPDATE ON `enrollment_news` FOR EACH ROW
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
        Schema::dropIfExists('enrollment_news');
    }
}

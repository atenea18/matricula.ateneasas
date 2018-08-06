<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerPensumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pensum', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS insert_code_pensum;
            CREATE TRIGGER insert_code_pensum BEFORE INSERT ON `pensum` FOR EACH ROW
            BEGIN
            SET NEW.code_pensum = CONCAT(NEW.grade_id,NEW.asignatures_id, NEW.institution_id);
            END;
            
        ');
            \Illuminate\Support\Facades\DB::unprepared('
          DROP PROCEDURE IF EXISTS update_code_pensum;
            CREATE TRIGGER update_code_pensum BEFORE UPDATE ON `pensum` FOR EACH ROW
            BEGIN
            SET NEW.code_pensum = CONCAT(NEW.grade_id,NEW.asignatures_id, NEW.institution_id);
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
        Schema::table('pensum', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_pensum`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_pensum`');
        });
    }
}

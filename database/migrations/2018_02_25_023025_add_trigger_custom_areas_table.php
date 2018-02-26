<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerCustomAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_areas', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS insert_code_custom_areas;
            CREATE TRIGGER insert_code_custom_areas BEFORE INSERT ON `custom_areas` FOR EACH ROW
            BEGIN
            SET NEW.code_custom_area = CONCAT(NEW.areas_id, NEW.institution_id);
            END;
            
        ');
            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS update_code_custom_areas;
            CREATE TRIGGER update_code_custom_areas BEFORE UPDATE ON `custom_areas` FOR EACH ROW
            BEGIN
            SET NEW.code_custom_area = CONCAT(NEW.areas_id, NEW.institution_id);
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
        Schema::table('custom_areas', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_custom_areas`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_custom_areas`');
        });
    }
}

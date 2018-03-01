<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerToCustomAsignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_asignatures', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS insert_code_custom_asignatures;
            CREATE TRIGGER insert_code_custom_asignatures BEFORE INSERT ON `custom_asignatures` FOR EACH ROW
            BEGIN
            SET NEW.code_custom_asignature = CONCAT(NEW.asignatures_id, NEW.institution_id);
            END;
            
        ');
            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS update_code_custom_asignatures;
            CREATE TRIGGER update_code_custom_asignatures BEFORE UPDATE ON `custom_asignatures` FOR EACH ROW
            BEGIN
            SET NEW.code_custom_asignature = CONCAT(NEW.asignatures_id, NEW.institution_id);
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
        Schema::table('custom_asignatures', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_custom_asignatures`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_custom_asignatures`');
        });
    }
}

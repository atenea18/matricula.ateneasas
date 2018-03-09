<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerGroupPensumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('', function (Blueprint $table) {
            //
        });
        Schema::table('group_pensum', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS insert_code_group_pensum;
            CREATE TRIGGER insert_code_group_pensum BEFORE INSERT ON `group_pensum` FOR EACH ROW
            BEGIN
            SET NEW.code_group_pensum = CONCAT(NEW.group_id,NEW.asignatures_id);
            END;
            
        ');
            \Illuminate\Support\Facades\DB::unprepared('
          DROP PROCEDURE IF EXISTS update_code_group_pensum;
            CREATE TRIGGER update_code_group_pensum BEFORE UPDATE ON `group_pensum` FOR EACH ROW
            BEGIN
            SET NEW.code_group_pensum = CONCAT(NEW.group_id,NEW.asignatures_id);
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
        Schema::table('group_pensum', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_group_pensum`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_group_pensum`');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerToMessagesExpressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages_expressions', function (Blueprint $table) {

            \Illuminate\Support\Facades\DB::unprepared('
            DROP PROCEDURE IF EXISTS insert_code_messages_expressions;
            CREATE TRIGGER insert_code_messages_expressions BEFORE INSERT ON `messages_expressions` FOR EACH ROW
            BEGIN
            SET NEW.code_messages_expressions = CONCAT(NEW.messages_id,NEW.institution_id);
            END;            
        ');
            \Illuminate\Support\Facades\DB::unprepared('
          DROP PROCEDURE IF EXISTS update_code_messages_expressions;
            CREATE TRIGGER update_code_messages_expressions BEFORE UPDATE ON `messages_expressions` FOR EACH ROW
            BEGIN
            SET NEW.code_messages_expressions = CONCAT(NEW.messages_id,NEW.institution_id);
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
        Schema::table('messages_expressions', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `insert_code_messages_expressions`');
            \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER `update_code_messages_expressions`');
        });
    }
}

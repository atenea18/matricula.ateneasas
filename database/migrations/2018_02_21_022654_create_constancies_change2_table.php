<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstanciesChange2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('constancies', function (Blueprint $table) {
            $table->longText('firstFirm')->after('footer');
            $table->longText('firstRol')->after('firstFirm');

            $table->longText('secondFirm')->after('firstRol');
            $table->longText('secondRol')->after('secondFirm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('constancies', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstanciesChange3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('constancies', function (Blueprint $table) {
            $table->longText('firstFirm')->nullable()->change();
            $table->longText('firstRol')->nullable()->change();

            $table->longText('secondFirm')->nullable()->change();
            $table->longText('secondRol')->nullable()->change();
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

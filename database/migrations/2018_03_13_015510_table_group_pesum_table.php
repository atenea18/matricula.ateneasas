<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGroupPesumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_pensum', function (Blueprint $table) {
            $table->unsignedBigInteger('pensum_id')
                ->after('id')
                ->nullable();
            $table->foreign('pensum_id')
                ->references('id')
                ->on('pensum')
                ->onDelete('cascade');
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
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToCustomAsignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_asignatures', function (Blueprint $table) {
            $table->unsignedBigInteger('asignatures_id');
            $table->unsignedBigInteger('subjects_type_id');
            $table->unsignedBigInteger('institution_id');

            $table->foreign('asignatures_id')->references('id')->on('asignature');
            $table->foreign('subjects_type_id')->references('id')->on('subjects_type');
            $table->foreign('institution_id')->references('id')->on('institution');
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
            $table->dropForeign(['asignatures_id']);
            $table->dropForeign(['subjects_type_id']);
            $table->dropForeign(['institution_id']);

            $table->dropColumn('asignatures_id');
            $table->dropColumn('subjects_type_id');
            $table->dropColumn('institution_id');
        });
    }
}

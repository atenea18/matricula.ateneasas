<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToCustomAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_areas', function (Blueprint $table) {
            $table->unsignedBigInteger('areas_id');
            $table->unsignedBigInteger('subjects_type');
            $table->unsignedBigInteger('institution_id');

            $table->foreign('areas_id')->references('id')->on('areas');
            $table->foreign('subjects_type')->references('id')->on('subjects_type');
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
        Schema::table('custom_areas', function (Blueprint $table) {

            $table->dropForeign(['areas_id']);
            $table->dropForeign(['subjects_type_id']);
            $table->dropForeign(['institution_id']);

            $table->dropColumn('areas_id');
            $table->dropColumn('subjects_type_id');
            $table->dropColumn('institution_id');
        });
    }
}

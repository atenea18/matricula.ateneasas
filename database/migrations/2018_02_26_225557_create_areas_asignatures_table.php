<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasAsignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas_asignatures', function (Blueprint $table) {
            $table->bigInteger('custom_asignatures_id')->unsigned();
            $table->bigInteger('custom_areas_id')->unsigned();

            $table->primary('custom_asignatures_id', 'custom_areas_id');

            $table->index('custom_areas_id','fk_custom_asignature_has_custom_area_custom_area1_idx');
            $table->index('custom_asignatures_id','fk_custom_asignature_has_custom_area_custom_asignature1_idx');

            $table->foreign('custom_asignatures_id')
                ->references('id')->on('custom_asignatures')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('custom_areas_id')
                ->references('id')->on('custom_areas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas_asignatures');
    }
}

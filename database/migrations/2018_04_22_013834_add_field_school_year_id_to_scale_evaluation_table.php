<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldSchoolYearIdToScaleEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scale_evaluations', function (Blueprint $table) {
            $table->unsignedBigInteger('school_year_id')->after('institution_id')->default(1);
            $table->foreign('school_year_id')
                ->references('id')->on('schoolyears');
        });
    }
    /**

     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scale_evaluations', function (Blueprint $table) {
            //
        });
    }
}

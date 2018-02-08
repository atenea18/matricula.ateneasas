<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('enrollment', function (Blueprint $table) {
        //     // Relación Sede
        //     $table->integer('headquarter_id')->nullable()->unsigned()->after('student_id');
        //     $table->foreign('headquarter_id')
        //           ->references('id')->on('headquarter')
        //           ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enrollment', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('institution_id');
            $table->text('header')->nullable();
            $table->string('rector_firm')->nullable();
            $table->string('rector_number_document')->nullable();
            $table->string('rector_place_expedition')->nullable();
            $table->string('secretary_firm')->nullable();
            $table->string('secretary_number_document')->nullable();
            $table->string('secretary_place_expedition')->nullable();
            $table->string('place_expedition_document')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('certificates');
    }
}

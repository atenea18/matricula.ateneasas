<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_institution', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 191)->unique();
            $table->string('name', 191);

            $table->unsignedBigInteger('config_type_id');
            $table->foreign('config_type_id')
                ->references('id')->on('config_type');

            $table->unsignedBigInteger('config_options_id');
            $table->foreign('config_options_id')
                ->references('id')->on('config_options');

            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')
                ->references('id')->on('institution');

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
        Schema::dropIfExists('config_institution');
    }
}

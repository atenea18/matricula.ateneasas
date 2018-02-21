<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constancies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('content');

            // Relacion Con la tabla administrativo
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')
                  ->references('id')->on('constancy_types')
                  ->onDelete('cascade');

            // Relación con la institución
            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')
                  ->references('id')->on('institution')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('constancies');
    }
}

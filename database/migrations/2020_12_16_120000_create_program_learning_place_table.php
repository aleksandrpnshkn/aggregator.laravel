<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramLearningPlaceTable extends Migration
{
    public function up()
    {
        Schema::create('program_learning_place', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')
                ->references('id')
                ->on('programs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('learning_place_id');
            $table->foreign('learning_place_id')
                ->references('id')
                ->on('learning_places')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_learning_place');
    }
}

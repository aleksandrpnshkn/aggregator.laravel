<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramDrivingCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('program_driving_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')
                ->references('id')
                ->on('programs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('driving_category_id');
            $table->foreign('driving_category_id')
                ->references('id')
                ->on('driving_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_driving_category');
    }
}

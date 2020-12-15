<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrivingSchoolDrivingCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('driving_school_driving_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('driving_school_id');
            $table->foreign('driving_school_id')
                ->references('id')
                ->on('driving_schools')
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
        Schema::dropIfExists('driving_school_driving_category');
    }
}

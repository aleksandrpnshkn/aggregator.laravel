<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConclusionDrivingCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('conclusion_driving_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('conclusion_id');
            $table->foreign('conclusion_id')
                ->references('id')
                ->on('conclusions')
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
        Schema::dropIfExists('conclusion_driving_category');
    }
}

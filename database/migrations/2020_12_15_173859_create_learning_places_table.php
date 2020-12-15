<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearningPlacesTable extends Migration
{
    public function up()
    {
        Schema::create('learning_places', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('type', 100);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('driving_school_id');
            $table->foreign('driving_school_id')
                ->references('id')
                ->on('driving_schools')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('learning_places');
    }
}

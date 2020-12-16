<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_retraining')->default(false);
            $table->boolean('is_akpp')->default(false);

            $table->unsignedDecimal('price', 9, 2)->nullable();
            $table->string('price_type', 30)->nullable();

            $table->unsignedBigInteger('driving_school_id');
            $table->foreign('driving_school_id')
                ->references('id')
                ->on('driving_schools')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
}

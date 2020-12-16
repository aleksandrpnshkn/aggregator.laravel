<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConclusionResultsTable extends Migration
{
    public function up()
    {
        Schema::create('conclusion_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->date('starts_at');
            // Судя по статьям в интернете, раньше выдавали бессрочные заключения
            // https://www.kommersant.ru/doc/3925667
            // https://rg.ru/2019/04/08/kak-sotrudniki-gibdd-budut-proveriat-avtoshkoly.html
            $table->date('ends_at')->nullable();

            $table->unsignedBigInteger('conclusion_id');
            $table->foreign('conclusion_id')
                ->references('id')
                ->on('conclusions')
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
        Schema::dropIfExists('conclusion_results');
    }
}

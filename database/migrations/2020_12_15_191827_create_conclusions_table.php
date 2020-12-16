<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * У автошкол множество заключений (считая просроченные).
 * У заключения множество результатов заключения.
 * Водительские категории указываются у самого заключения, т.к. в рамках одного заключения они для всех результатов одинаковые.
 * Заключение через результаты связано со множеством учебных мест.
 * Каждое учебное место может иметь множество результатов заключений (считая просроченные).
 * Сроки действия указываются в результате заключения, т.к. сроки для каждого результата индивидуальны.
 */
class CreateConclusionsTable extends Migration
{
    public function up()
    {
        Schema::create('conclusions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('number', 100)->nullable()->unique()->comment('Номер заключения');

            $table->unsignedBigInteger('driving_school_id');
            $table->foreign('driving_school_id')
                ->references('id')
                ->on('driving_schools')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('verified_by_user_id')->nullable();
            $table->foreign('verified_by_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conclusions');
    }
}

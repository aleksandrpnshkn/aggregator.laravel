<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrivingSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('driving_schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('name')->nullable();
            $table->string('legal_name');
            $table->string('slug', 100)->unique();

            $table->string('inn', 12)->nullable()->unique();
            $table->string('type', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('post_status', 30)->default('draft');

            $table->string('school_status', 30)->default('is_open');
            $table->date('open_date')->nullable();
            $table->date('close_date')->nullable();

            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('driving_schools');
    }
}

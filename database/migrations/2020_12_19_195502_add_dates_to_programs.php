<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToPrograms extends Migration
{
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['starts_at', 'ends_at']);
        });
    }
}

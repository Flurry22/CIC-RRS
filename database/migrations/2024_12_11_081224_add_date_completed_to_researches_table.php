<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateCompletedToResearchesTable extends Migration
{
    public function up()
    {
        Schema::table('researches', function (Blueprint $table) {
            // Add the date_completed column as nullable datetime
            $table->timestamp('date_completed')->nullable();
        });
    }

    public function down()
    {
        Schema::table('researches', function (Blueprint $table) {
            $table->dropColumn('date_completed');
        });
    }
}

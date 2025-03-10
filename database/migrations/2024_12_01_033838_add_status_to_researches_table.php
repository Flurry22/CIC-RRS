<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('researches', function (Blueprint $table) {
        $table->enum('status', ['On-Going', 'Finished'])->default('On-Going');
    });
}

public function down()
{
    Schema::table('researches', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};

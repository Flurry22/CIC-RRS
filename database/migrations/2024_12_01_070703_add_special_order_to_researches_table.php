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
        $table->string('special_order')->nullable(); // Add nullable column for special order
    });
}

public function down()
{
    Schema::table('researches', function (Blueprint $table) {
        $table->dropColumn('special_order');
    });
}
};

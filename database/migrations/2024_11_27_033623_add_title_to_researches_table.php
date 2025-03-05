<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('researches', function (Blueprint $table) {
        // Modify the 'title' column to make it nullable
        $table->string('title')->nullable()->change();
    });
}

public function down()
{
    Schema::table('researches', function (Blueprint $table) {
        // Rollback: Make the 'title' column non-nullable
        $table->string('title')->nullable(false)->change();
    });
}
};

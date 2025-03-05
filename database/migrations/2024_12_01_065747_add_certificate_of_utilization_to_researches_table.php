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
        $table->string('certificate_of_utilization')->nullable(); // Add nullable column for certificate
    });
}

public function down()
{
    Schema::table('researches', function (Blueprint $table) {
        $table->dropColumn('certificate_of_utilization');
    });
}
};

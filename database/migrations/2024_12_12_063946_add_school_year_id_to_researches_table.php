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
            $table->unsignedBigInteger('school_year_id')->nullable(); // Add the foreign key
            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade'); // Foreign key constraint
        });
    }
    
    public function down()
    {
        Schema::table('researches', function (Blueprint $table) {
            $table->dropForeign(['school_year_id']); // Drop the foreign key
            $table->dropColumn('school_year_id'); // Drop the column
        });
    }
};

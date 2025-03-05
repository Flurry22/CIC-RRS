<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_files', function (Blueprint $table) {
            $table->id(); // auto-incrementing ID column
            $table->string('title'); // column for the title of the file
            $table->string('research_file'); // column for storing the file path or filename
            $table->timestamps(); // includes created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('research_files');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchesTable extends Migration
{
    public function up()
    {
        Schema::create('researches', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description');
            $table->unsignedBigInteger('budget');
            $table->unsignedBigInteger('leader_id');  // Program Leader (linked to a researcher)
            $table->enum('type', ['program', 'project', 'study']);
            $table->unsignedBigInteger('program_id'); // Linked to a program (NOT college)
            $table->enum('funding_type', ['externally_funded', 'internally_funded']);
            $table->date('deadline');
            $table->timestamps();

            // Foreign keys
            $table->foreign('leader_id')->references('id')->on('researchers')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('researches');
    }
}

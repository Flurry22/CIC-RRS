<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearcherResearchTable extends Migration
{
    public function up()
    {
        Schema::create('researcher_research', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('research_id');
            $table->unsignedBigInteger('researcher_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('research_id')->references('id')->on('researches')->onDelete('cascade');
            $table->foreign('researcher_id')->references('id')->on('researchers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('researcher_research');
    }
}

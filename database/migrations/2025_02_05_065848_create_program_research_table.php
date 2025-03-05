<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('program_research', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('research_id'); // Explicitly set as unsignedBigInteger
            $table->unsignedBigInteger('program_id');
            $table->timestamps();

            $table->foreign('research_id')->references('id')->on('researches')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_research');
    }
};

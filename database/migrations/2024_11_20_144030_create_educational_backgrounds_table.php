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
    Schema::create('educational_backgrounds', function (Blueprint $table) {
        $table->id();
        $table->foreignId('researcher_id')->constrained('researchers')->onDelete('cascade'); // Assuming the researcher has many educational backgrounds
        $table->string('past_college');
        $table->string('course');
        $table->year('education_year');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_backgrounds');
    }
};

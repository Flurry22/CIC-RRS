<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolYearsTable extends Migration
{
    public function up()
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->string('school_year'); // e.g., "2024-2025"
            $table->date('first_sem_start');
            $table->date('first_sem_end');
            $table->date('second_sem_start');
            $table->date('second_sem_end');
            $table->date('off_sem_start')->nullable();
            $table->date('off_sem_end')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_years');
    }
}

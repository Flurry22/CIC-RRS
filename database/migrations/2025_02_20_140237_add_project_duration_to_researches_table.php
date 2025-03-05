<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('researches', function (Blueprint $table) {
            $table->string('project_duration')->nullable()->after('deadline'); // Change 'some_column' to the actual column name you want it after
        });
    }

    public function down(): void
    {
        Schema::table('researches', function (Blueprint $table) {
            $table->dropColumn('project_duration');
        });
    }
};



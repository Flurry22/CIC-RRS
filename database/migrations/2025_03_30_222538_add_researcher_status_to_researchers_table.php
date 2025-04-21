<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('researchers', function (Blueprint $table) {
            $table->enum('researcher_status', ['Active', 'Inactive'])->default('Active');
        });
    }

    public function down(): void
    {
        Schema::table('researchers', function (Blueprint $table) {
            $table->dropColumn('researcher_status');
        });
    }
};

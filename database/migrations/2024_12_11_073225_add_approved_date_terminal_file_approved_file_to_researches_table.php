<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedDateTerminalFileApprovedFileToResearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('researches', function (Blueprint $table) {
            // Add approved_date as a nullable date field
            $table->date('approved_date')->nullable();

            // Add terminal_file as a nullable string (file path)
            $table->string('terminal_file')->nullable();

            // Add approved_file as a nullable string (file path)
            $table->string('approved_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('researches', function (Blueprint $table) {
            // Drop the newly added columns
            $table->dropColumn(['approved_date', 'terminal_file', 'approved_file']);
        });
    }
}

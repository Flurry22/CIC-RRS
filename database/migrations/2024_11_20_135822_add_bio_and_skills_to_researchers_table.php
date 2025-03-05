<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBioAndSkillsToResearchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('researchers', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('profile_picture'); // Adds a bio column after last_name
            $table->json('skills')->nullable()->after('bio');    // Adds a skills column after bio
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('researchers', function (Blueprint $table) {
            $table->dropColumn(['bio', 'skills']); // Removes both columns
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToResearcherResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('researcher_research', function (Blueprint $table) {
            // Add a new column for role with a default value
            $table->string('role')->default('member')->after('researcher_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('researcher_research', function (Blueprint $table) {
            // Drop the role column
            $table->dropColumn('role');
        });
    }
}

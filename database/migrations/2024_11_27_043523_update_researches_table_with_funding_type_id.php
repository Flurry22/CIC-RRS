<?php

// database/migrations/XXXX_XX_XX_update_researches_table_with_funding_type_id.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateResearchesTableWithFundingTypeId extends Migration
{
    public function up()
    {
        // Add funding_type_id column and remove old funding_type column
        Schema::table('researches', function (Blueprint $table) {
            // Add the new foreign key column for funding_type_id
            $table->unsignedBigInteger('funding_type_id')->after('title');

            // Create foreign key relationship
            $table->foreign('funding_type_id')->references('id')->on('funding_types')->onDelete('cascade');
            
            // Drop the old funding_type column if it exists
            $table->dropColumn('funding_type');
        });
    }

    public function down()
    {
        // Reverse the changes made in the up() method
        Schema::table('researches', function (Blueprint $table) {
            // Add back the old funding_type column
            $table->enum('funding_type', ['externally_funded', 'internally_funded'])->after('title');

            // Drop the foreign key and funding_type_id column
            $table->dropForeign(['funding_type_id']);
            $table->dropColumn('funding_type_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundingTypesTable extends Migration
{
    public function up()
    {
        Schema::create('funding_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // e.g., 'Externally Funded', 'Internally Funded'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('funding_types');
    }
}

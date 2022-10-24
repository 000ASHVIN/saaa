<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToCpdsCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cpds` CHANGE `category` `category` ENUM('tax', 'ethics', 'auditing', 'verifiable', 'accounting', 'unstructed', 'non_verifiable') default NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `cpds` CHANGE `category` `category` ENUM('tax', 'ethics', 'auditing', 'verifiable', 'accounting', 'unstructed', 'non_verifiable') default NULL;");
    }
}

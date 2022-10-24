<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryIdToSolutionSubFolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solution_sub_folders', function (Blueprint $table) {
            $table->integer('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solution_sub_folders', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
}

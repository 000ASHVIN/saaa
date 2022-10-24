<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionSubFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solution_sub_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solution_folder_id')->nullable();
            $table->string('name');
            $table->bigInteger('sub_folder_id');
            $table->longText('description')->nullable();
            $table->integer('visibility')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('solution_sub_folders');
    }
}

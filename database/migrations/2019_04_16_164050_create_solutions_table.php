<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('solution_article_id')->nullable();
            $table->bigInteger('solution_folder_id')->nullable();
            $table->bigInteger('solution_sub_folder_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_text')->nullable();
            $table->json('tags')->nullable();
            $table->bigInteger('hits')->nullable();
            $table->json('seo_data')->nullable();
            $table->integer('thumbs_up')->nullable();
            $table->integer('thumbs_down')->nullable();
            $table->integer('feedback_count')->nullable();
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
        Schema::drop('solutions');
    }
}

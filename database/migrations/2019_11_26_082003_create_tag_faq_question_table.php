<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagFaqQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_faq_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->unsigned()->index();
            $table->integer('faq_question_id')->unsigned()->index();
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
        Schema::drop('tag_faq_question');
    }
}

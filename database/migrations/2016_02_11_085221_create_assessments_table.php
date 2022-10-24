<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid')->unique()->index();
            $table->string('title');
            $table->text('instructions');
            $table->integer('cpd_hours')->unsigned()->default(1);
            $table->integer('pass_percentage')->unsigned()->default(50);
            $table->integer('time_limit_minutes')->unsigned()->default(0);
            $table->integer('maximum_attempts')->unsigned()->default(0);
            $table->boolean('randomize_questions_order')->default(false);
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
        Schema::drop('assessments');
    }
}

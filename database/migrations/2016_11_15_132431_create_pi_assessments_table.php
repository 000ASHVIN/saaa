<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePiAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pi_user_id')->unisgined()->index();
            $table->boolean('conduct')->default(0);
            $table->boolean('cpd')->default(0);
            $table->boolean('engagement')->default(0);
            $table->boolean('technical')->default(0);
            $table->boolean('resources')->default(0);
            $table->boolean('reviews')->default(0);
            $table->boolean('standards')->default(0);
            $table->boolean('technology')->default(0);
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
        Schema::drop('pi_assessments');
    }
}

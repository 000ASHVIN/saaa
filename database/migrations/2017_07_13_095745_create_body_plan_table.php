<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodyPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('body_id')->unsigned();
            $table->integer('plan_id')->unsigned();

            $table->foreign('body_id')->references('id')->on('bodies')->onDelete('cascade');
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
        Schema::drop('body_plan');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotTableForPracticePlanTabsFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_plan_tabs_features', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('practice_plan_tab_id')->unsigned();
            $table->integer('feature_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('practice_plan_tabs_features');
    }
}

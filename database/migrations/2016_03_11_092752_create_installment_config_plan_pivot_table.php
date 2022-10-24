<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallmentConfigPlanPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_config_plan', function (Blueprint $table) {
            $table->integer('installment_config_id')->unsigned()->index();
            $table->integer('plan_id')->unsigned()->index();
            $table->primary(['installment_config_id', 'plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('installment_config_plan');
    }
}

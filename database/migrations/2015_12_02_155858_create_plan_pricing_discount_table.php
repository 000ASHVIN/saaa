<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanPricingDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_pricing_discount', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pricing_id')->unsigned()->index();
            $table->integer('plan_id')->unisgned()->index();
            $table->enum('discount_type', ['amount', 'percentage'])->default('percentage');
            $table->double('discount_value')->default(0);
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
        Schema::drop('plan_pricing_discount');
    }
}

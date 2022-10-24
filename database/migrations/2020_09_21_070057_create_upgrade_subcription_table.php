<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpgradeSubcriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upgrade_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id');
            $table->integer('new_plan_id');
            $table->integer('user_id');
            $table->text('data')->nullable();
            $table->boolean('is_completed')->default(false);
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
        Schema::drop('upgrade_subscriptions');
    }
}

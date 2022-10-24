<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionUpgradeRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_upgrades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->integer('note_id')->nullable();
            $table->integer('new_subscription_package')->nullable();
            $table->integer('old_subscription_package')->nullable();
            $table->boolean('completed')->default(false);
            $table->longText('reason');
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
        Schema::drop('subscription_upgrades');
    }
}

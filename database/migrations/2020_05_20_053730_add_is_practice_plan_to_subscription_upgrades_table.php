<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPracticePlanToSubscriptionUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_upgrades', function (Blueprint $table) {
            $table->integer('is_practice_plan')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_upgrades', function (Blueprint $table) {
            $table->dropColumn('is_practice_plan');
        });
    }
}

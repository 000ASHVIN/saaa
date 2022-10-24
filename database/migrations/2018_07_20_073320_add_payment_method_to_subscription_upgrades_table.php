<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentMethodToSubscriptionUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_upgrades', function (Blueprint $table) {
            $table->string('payment_method');
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
            $table->dropColumn('payment_method');
        });
    }
}
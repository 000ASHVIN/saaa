<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlternativeInformationToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('sms_confirmed');
            $table->boolean('email_confirmed');
            $table->longText('voice_recording');
            $table->enum('payment_method', ['eft','debit_order','credit_card','other'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['sms_confirmed', 'email_confirmed','voice_recording','payment_method']);
        });
    }
}

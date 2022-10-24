<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuspendNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspended_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('day');
            $table->date('sent_on');
            $table->string('subject');
            $table->integer('user_id');
            $table->date('invoice_date');
            $table->string('email_address');
            $table->integer('invoice_reference');
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
        Schema::drop('suspended_notification');
    }
}

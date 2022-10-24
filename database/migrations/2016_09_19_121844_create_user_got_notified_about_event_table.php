<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGotNotifiedAboutEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_notified', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('email_address');
            $table->string('subscription');
            $table->string('mobile');
            $table->string('subject_line');
            $table->string('event_name');
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
        Schema::drop('event_notified');
    }
}

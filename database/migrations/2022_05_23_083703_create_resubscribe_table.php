<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResubscribeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resubscribe', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->string('email');
            $table->boolean('unsubscribe_all')->default(0);
            $table->string('subscribed_types')->nullable();
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
        Schema::drop('resubscribe');
    }
}

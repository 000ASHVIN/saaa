<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePiAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pi_user_id')->unsigned()->index();
            $table->string('line_one')->nullable();
            $table->string('line_two')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('code')->nullable();
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
        Schema::drop('pi_addresses');
    }
}

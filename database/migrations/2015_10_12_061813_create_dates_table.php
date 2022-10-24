<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dates', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('venue_id')->index()->unsigned();
            $table->datetime('date');
            $table->timestamps();
        });

        Schema::table('dates', function(Blueprint $table) {
            $table->foreign('venue_id')->references('id')->on('venues')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dates');
    }
}

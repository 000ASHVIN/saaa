<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QueueComboMailer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combo_mailers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->nullable();
            $table->date('sent_at')->nullable();
            $table->boolean('should_send')->default(false);
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
        Schema::drop('combo_mailers');
    }
}

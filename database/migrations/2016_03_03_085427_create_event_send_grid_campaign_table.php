<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSendGridCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_send_grid_campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned()->index();
            $table->integer('campaign_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('event_send_grid_campaign');
    }
}

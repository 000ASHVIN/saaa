<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PivotTableForSeriesVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('series_id')->unsigned()->index();
            $table->integer('video_id')->unsigned()->index();
            $table->integer('sequence')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('series_videos');
    }
}

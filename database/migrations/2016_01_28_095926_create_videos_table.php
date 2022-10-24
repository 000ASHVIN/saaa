<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('video_provider_id')->unsigned()->index();
            $table->string('title');
            $table->string('reference');
            $table->boolean('can_be_downloaded')->default(true);
            $table->string('download_link')->nullable();
            $table->integer('width')->unsigned()->default(640);
            $table->integer('height')->unsigned()->default(360);
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
        Schema::drop('videos');
    }
}

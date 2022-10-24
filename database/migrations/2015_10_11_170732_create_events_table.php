<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration
{

    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['webinar', 'seminar', 'conference']);
            $table->string('name', 255);
            $table->string('slug')->nullable();
            $table->text('description');
            $table->string('featured_image');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('next_date')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->boolean('is_redirect')->default(false);
            $table->string('redirect_url')->nullable()->default(null);
            $table->datetime('published_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('events');
    }
}
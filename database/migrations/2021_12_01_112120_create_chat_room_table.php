<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_room', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('admin_id')->default(0);
            $table->string('name')->nullable();
            $table->tinyInteger('is_ended')->default(0);
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
        Schema::drop('chat_room');
    }
}

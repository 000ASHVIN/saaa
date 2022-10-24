<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('chat_room_id');
            $table->string('message')->nullable();
            $table->string('author')->nullable();
            $table->tinyInteger('is_admin')->default(0);
            $table->tinyInteger('seen')->default(0);
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
        Schema::drop('chat_messages');
    }
}

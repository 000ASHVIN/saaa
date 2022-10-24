<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->string('slug');
            $table->date('expired_at');
            $table->string('reference');
            $table->integer('agent_id');
            $table->integer('thread_id');
            $table->bigInteger('rating');
            $table->longText('description');
            $table->enum('status', ['open', 'pending', 'closed']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->date('deleted_at')->nullable();
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
        Schema::drop('support_tickets');
    }
}

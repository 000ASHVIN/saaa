<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebinarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pricing_id')->unsigned()->index();
            $table->boolean('is_active')->default(true);
            $table->enum('type', ['live', 'recording']);
            $table->string('url');
            $table->string('code')->nullable();
            $table->string('passcode')->nullable();
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
        Schema::drop('webinars');
    }
}

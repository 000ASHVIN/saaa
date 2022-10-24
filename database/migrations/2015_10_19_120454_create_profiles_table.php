<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->text('about_me')->nullable();
            $table->string('id_number')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('avatar')->default('/assets/frontend/images/avatar2.jpg');
            $table->string('cell')->nullable();
            $table->string('position')->nullable();
            $table->datetime('birthday')->nullable();
            $table->enum('gender', ['m', 'f'])->nullable();
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
        Schema::drop('profiles');
    }
}

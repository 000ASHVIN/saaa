<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePiUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('registered')->nullable();
            $table->string('body')->nullable();
            $table->string('email');
            $table->string('declined')->nullable();
            $table->string('declined_reason')->nullable();
            $table->string('aware')->nullable();
            $table->string('aware_description')->nullable();
            $table->string('legal_entity')->nullable();
            $table->string('negligence')->nullable();
            $table->string('practice_abroad')->nullable();
            $table->string('work_abroad')->nullable();
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
        Schema::drop('pi_users');
    }
}

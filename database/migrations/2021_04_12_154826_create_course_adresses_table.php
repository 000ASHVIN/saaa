<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCourseAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('course_id');
            $table->integer('subscription_id');
            $table->integer('id_number');
            $table->text('street_name')->nullable();
            $table->text('building')->nullable();
            $table->text('suburb')->nullable();
            $table->text('city')->nullable();
            $table->text('province')->nullable();
            $table->text('country')->nullable();
            $table->text('postal_code')->nullable();
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
        Schema::drop('course_addresses');
    }
}

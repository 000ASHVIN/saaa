<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySystemSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop Existing Plans
        Schema::drop('plans');

        // Create New Plans Table
        Schema::create('plans', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 7, 2)->default('0.00');
            $table->string('interval')->default('month');
            $table->smallInteger('interval_count')->default(1);
            $table->smallInteger('trial_period_days')->nullable();
            $table->smallInteger('sort_order')->nullable();
            $table->boolean('inactive')->default(false);
            $table->timestamps();
        });

        // Create Plan Features Table
        Schema::create('features', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('value')->nullable();
            $table->string('description')->nullable();
            $table->smallInteger('sort_order')->nullable();
            $table->timestamps();
        });

        // Drop Existing subscriptions
        Schema::drop('subscriptions');

        // Create Subscriptions
        Schema::create('subscriptions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->string('name');
            $table->boolean('suspended')->default(false);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

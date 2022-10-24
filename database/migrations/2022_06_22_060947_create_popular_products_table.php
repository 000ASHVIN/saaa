<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopularProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popular_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model')->nullable();
            $table->integer('model_id')->default(0);
            $table->integer('order');
            $table->tinyInteger('status');
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
        Schema::drop('popular_products');
    }
}

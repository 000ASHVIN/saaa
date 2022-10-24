<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_active')->default(true);
            $table->string('topic');
            $table->integer('year');
            $table->string('title');
            $table->decimal('price');
            $table->boolean('is_physical')->default(false);
            $table->boolean('allow_out_of_stock_order')->default(true);
            $table->integer('stock')->default(0);
            $table->integer('cpd_hours')->default(0);
            $table->softDeletes();
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
        Schema::drop('store_products');
    }
}

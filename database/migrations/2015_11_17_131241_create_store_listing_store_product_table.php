<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreListingStoreProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_listing_store_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->boolean('is_combo')->default(false);
            $table->enum('discount_type', ['percentage', 'amount'])->default('percentage');
            $table->decimal('discount', 10, 2)->default(0);
            $table->integer('sort_order')->default(0);
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
        Schema::drop('store_listing_store_product');
    }
}

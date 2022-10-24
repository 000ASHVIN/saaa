<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreListingRelatedListingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_listing_related_listing', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id')->index()->unsigned();
            $table->integer('related_listing_id')->index()->unsigned();
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
        Schema::drop('store_listing_related_listing');
    }
}

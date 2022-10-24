<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceStatusEnumWithStatusIdOnStoreShippingInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_shipping_information', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->integer('status_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_shipping_information', function (Blueprint $table) {
            $table->enum('status', ['Awaiting payment', 'Awaiting stock', 'At office', 'En route', 'Delivered', 'Picked up', 'Cancelled']);
            $table->dropColumn('status_id');
        });
    }
}

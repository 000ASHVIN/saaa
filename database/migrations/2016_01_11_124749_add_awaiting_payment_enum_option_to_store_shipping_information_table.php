<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAwaitingPaymentEnumOptionToStoreShippingInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE store_shipping_information CHANGE COLUMN status status ENUM('Awaiting payment','Awaiting stock', 'At office', 'En route', 'Delivered', 'Picked up', 'Cancelled')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE store_shipping_information CHANGE COLUMN status status ENUM('Awaiting stock', 'At office', 'En route', 'Delivered', 'Picked up', 'Cancelled')");
    }
}

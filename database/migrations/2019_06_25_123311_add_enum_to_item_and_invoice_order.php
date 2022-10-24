<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumToItemAndInvoiceOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `items` CHANGE `type` `type` ENUM('subscription','product','ticket','shipping','course') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `invoice_orders` CHANGE `type` `type` ENUM('subscription','store','event','course') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
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

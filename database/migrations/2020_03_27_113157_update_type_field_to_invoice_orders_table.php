<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTypeFieldToInvoiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `invoice_orders` CHANGE `type` `type` ENUM('subscription','store','event','course','webinar') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `invoice_orders` CHANGE `type` `type` ENUM('subscription','store','event','course','webinar') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    }
}

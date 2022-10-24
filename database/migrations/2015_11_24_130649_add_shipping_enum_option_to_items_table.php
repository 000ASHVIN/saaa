<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingEnumOptionToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE items CHANGE COLUMN type type ENUM('subscription', 'product', 'ticket', 'shipping')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE items CHANGE COLUMN type type ENUM('subscription', 'product', 'ticket')");
    }
}

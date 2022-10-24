<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasBeenContactedFieldToDebitOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debit_orders', function (Blueprint $table) {
            $table->boolean('has_been_contacted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('debit_orders', function (Blueprint $table) {
            $table->dropColumn('has_been_contacted');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvalidDebitOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invalid_debit_order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('result');
            $table->string('message');
            $table->integer('user_id');
            $table->string('reference');
            $table->string('branch_code');
            $table->string('customer_code');
            $table->string('account_number');
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
        Schema::drop('invalid_debit_order_details');
    }
}

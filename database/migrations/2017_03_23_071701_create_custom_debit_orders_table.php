<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomDebitOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_debit_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('id_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile');
            $table->string('reference');
            $table->string('bank');
            $table->string('number');
            $table->string('type');
            $table->string('branch_name');
            $table->integer('billable_date');
            $table->string('branch_code');
            $table->date('start_date');
            $table->date('final_date');
            $table->decimal('amount', 10, 2);
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
        Schema::drop('custom_debit_orders');
    }
}

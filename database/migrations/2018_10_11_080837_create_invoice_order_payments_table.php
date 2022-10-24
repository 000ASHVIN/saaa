<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_order_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->integer('invoice_order_id')->unsigned();
            $table->timestamp('date_of_payment')->nullable();
            $table->enum('method',['eft','cc', 'wallet', 'debit_order'])->nullable();
            $table->enum('tags', ['payment', 'discount']);
            $table->longText('notes')->nullable();
            $table->foreign('invoice_order_id')->references('id')->on('invoice_orders')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice_order_payments');
    }
}

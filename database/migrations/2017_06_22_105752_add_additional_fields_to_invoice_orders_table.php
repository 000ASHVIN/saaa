<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsToInvoiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_orders', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('reference')->unique();
            $table->double('discount', 10, 2);
            $table->double('vat_rate', 10, 2);
            $table->decimal('sub_total', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->timestamp('date_converted')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->enum('type', ['subscription', 'store', 'event']);
            $table->boolean('paid');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice_orders');
    }
}

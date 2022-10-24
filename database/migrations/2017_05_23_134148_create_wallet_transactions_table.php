<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('amount')->unsigned();
            $table->integer('wallet_id')->unsigned();
            $table->string('invoice_reference')->nullable();
            $table->enum('type',['debit','credit'])->nullable();
            $table->enum('method',['eft','cc', 'wallet'])->nullable();
            $table->enum('category',['store','event', 'subscription', 'payment', 'deduction'])->nullable();
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
        Schema::drop('wallet_transactions');
    }
}

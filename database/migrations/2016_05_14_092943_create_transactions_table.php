<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('invoice_id')->unsigned()->index();
            $table->enum('type', ['debit', 'credit']);
            $table->enum('display_type', ['Invoice', 'Payment', 'Credit Note', 'Debit Note', 'Adjustment']);
            $table->enum('status', ['Open', 'Closed', 'Cleared', 'Uncleared', 'Reconciled', 'Void'])->default('Cleared');
            $table->string('category')->nullable();
            $table->bigInteger('amount');
            $table->string('ref');
            $table->string('method')->nullable();
            $table->text('description')->nullable();
            $table->string('tags')->nullable();
            $table->datetime('date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}

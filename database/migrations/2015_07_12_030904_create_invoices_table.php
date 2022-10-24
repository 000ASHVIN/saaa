<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->unique();
            $table->integer('user_id')->unsigned();
            $table->double('discount', 10, 2);
            $table->double('vat_rate', 10, 2);
            $table->decimal('sub_total', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->timestamp('date_settled')->nullable();
            $table->boolean('paid');
            $table->timestamps();

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
        Schema::drop('invoices');
    }
}

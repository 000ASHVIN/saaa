<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsToDebitOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debit_orders', function (Blueprint $table) {
            $table->boolean('peach')->default(false);
            $table->boolean('active')->default(false);
            $table->boolean('bill_at_next_available_date')->default(false);
            $table->dateTime('next_debit_date')->nullable();
            $table->string('otp')->nullable();
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
            $table->dropColumn([
                'peach',
                'active',
                'bill_at_next_available_date',
                'next_debit_date',
                'otp'
            ]);
        });
    }
}

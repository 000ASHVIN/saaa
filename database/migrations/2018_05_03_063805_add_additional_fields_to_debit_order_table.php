<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsToDebitOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debit_orders', function (Blueprint $table) {
            $table->string('account_holder');
            $table->string('id_number');
            $table->string('registration_number');
            $table->enum('type_of_account', ['personal', 'company']);
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
            $table->dropColumn(['account_holder', 'id_number', 'registration_number', 'type_of_account']);
        });
    }
}

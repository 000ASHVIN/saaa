<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkipNextDebitToDebitOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debit_orders', function (Blueprint $table) {
            $table->boolean('skip_next_debit')->default(false);;
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
            $table->dropColumn('skip_next_debit');
        });
    }
}

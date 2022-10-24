<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE wallet_transactions CHANGE COLUMN method method ENUM('wallet_credit', 'wallet', 'eft', 'instant_eft', 'debit', 'cc', 'cash', 'snap_scan', 'pebble')");
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('invoice_order_refference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE wallet_transactions CHANGE COLUMN method method ENUM('wallet_credit', 'wallet', 'eft', 'instant_eft', 'debit', 'cc', 'cash', 'snap_scan', 'pebble')");
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn(['invoice_order_refference']);
        });
    }
}

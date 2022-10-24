<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDonationsColumnsToInvoiceOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_orders', function (Blueprint $table) {
            $table->integer('donation_id')->default(0)->unsigned()->index();
            $table->decimal('donation', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_orders', function (Blueprint $table) {
            $table->dropColumn('donation_id');
            $table->dropColumn('donation');
        });
    }
}

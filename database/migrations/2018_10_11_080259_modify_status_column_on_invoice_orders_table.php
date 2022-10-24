<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyStatusColumnOnInvoiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_orders', function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable();
            DB::statement("ALTER TABLE invoice_orders CHANGE COLUMN status status ENUM('paid', 'unpaid', 'cancelled', 'partial')");
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
            $table->dropColumn(['deleted_at']);
        });
    }
}

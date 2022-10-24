<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyInvoicesEnumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE invoices CHANGE COLUMN status status ENUM('paid', 'unpaid', 'cancelled', 'partial', 'credit noted')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE invoices CHANGE COLUMN status status ENUM('paid', 'unpaid', 'cancelled', 'partial', 'credit noted')");
    }
}

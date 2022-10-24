<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseEnumToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE invoices CHANGE type type ENUM('subscription', 'store', 'event', 'course') DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE invoices CHANGE type type ENUM('subscription', 'store', 'event', 'course') DEFAULT NULL;");
    }
}
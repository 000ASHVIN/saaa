<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOptionsToEnumTableForEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE events CHANGE category category ENUM('accounting_event', 'tax_event', 'all_cpd_subs', 'free') DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE events CHANGE category category ENUM('accounting_event', 'tax_event', 'all_cpd_subs', 'free') DEFAULT NULL;");
    }
}

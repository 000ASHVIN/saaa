<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasCertificateAndTicketIdColumnsToCpdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpds', function (Blueprint $table) {
            $table->boolean('has_certificate')->default(false);
            $table->integer('ticket_id')->unsigned()->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpds', function (Blueprint $table) {
            $table->dropColumn('has_certificate');
            $table->dropColumn('ticket_id');
        });
    }
}

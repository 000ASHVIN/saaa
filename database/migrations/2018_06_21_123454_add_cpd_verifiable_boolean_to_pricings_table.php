<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCpdVerifiableBooleanToPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricings', function (Blueprint $table) {
            $table->boolean('cpd_verifiable')->default(true);
            $table->boolean('attendance_certificate')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricings', function (Blueprint $table) {
            $table->dropColumn('cpd_verifiable');
            $table->dropColumn('attendance_certificate');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPiCoverToPreCpdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_cpds', function (Blueprint $table) {
            $table->boolean('pi_cover');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_cpds', function (Blueprint $table) {
            $table->dropColumn('pi_cover');
        });
    }
}

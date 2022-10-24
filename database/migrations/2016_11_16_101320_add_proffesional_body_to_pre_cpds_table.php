<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProffesionalBodyToPreCpdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_cpds', function (Blueprint $table) {
            $table->string('proffesional_body');
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
            $table->dropColumn('proffesional_body');
        });
    }
}

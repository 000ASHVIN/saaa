<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoIdAndTypeFieldToCpdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpds', function (Blueprint $table) {
            $table->integer('video_id')->index()->unsigned()->default(0);
            $table->string('cpd_type')->index()->nullable();
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
            $table->dropColumn('discount');
            $table->dropColumn('cpd_type');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToCourseProcess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_process', function (Blueprint $table) {
            $table->string('type', 100)->nullable()->default('download_brochure');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_process', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}

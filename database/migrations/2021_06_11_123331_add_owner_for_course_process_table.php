<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerForCourseProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_process', function (Blueprint $table) {
            $table->integer('owner_id')->nullable()->default(0)->unsigned()->index();
            $table->integer('lead_status_id')->nullable()->default(0)->unsigned()->index();
            $table->tinyInteger('is_converted')->nullable()->default(0)->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('owner_id');
        $table->dropColumn('lead_status_id');
        $table->dropColumn('is_converted');
    }
}

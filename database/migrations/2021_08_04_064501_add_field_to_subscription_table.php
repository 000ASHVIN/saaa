<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('completed_semester')->default(0);
            $table->string('course_type', 100)->nullable()->default('short');
            $table->integer('full_payment')->unsigned()->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('completed_semester');
            $table->dropColumn('course_type');
            $table->dropColumn('full_payment');
        });
    }
}

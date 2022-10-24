<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationsStatusForEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('notification_status', ['not_scheduled', 'scheduled', 'in_progress', 'completed'])->default('not_scheduled');
            $table->datetime('notification_schedule');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('notification_status');
            $table->dropColumn('notification_schedule');
        });
    }
}

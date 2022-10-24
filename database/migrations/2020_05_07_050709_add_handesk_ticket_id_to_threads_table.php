<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHandeskTicketIdToThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->integer('handesk_ticket_id')->unsigned()->nullable()->default(0)->index();
            $table->integer('user_id')->unsigned()->nullable()->default(0)->index();
            $table->longText('description')->nullable();
            $table->integer('agent_id')->unsigned()->nullable()->default(0)->index();
            $table->integer('agent_group_id')->unsigned()->nullable()->default(0)->index();
            $table->tinyInteger('priority')->default(1);

            DB::statement("ALTER TABLE threads 
                CHANGE COLUMN `status` 
                `status` enum('new', 'open', 'pending', 'solved', 'closed', 'merged', 'spam') 
                NOT NULL DEFAULT 'new'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->dropColumn('handesk_ticket_id');
            $table->dropColumn('user_id');
            $table->dropColumn('description');
            $table->dropColumn('agent_id');
            $table->dropColumn('agent_group_id');
            $table->dropColumn('priority');

            DB::statement("ALTER TABLE threads 
                CHANGE COLUMN `status` 
                `status` enum('open', 'closed') 
                NOT NULL DEFAULT 'open'");
        });
    }
}

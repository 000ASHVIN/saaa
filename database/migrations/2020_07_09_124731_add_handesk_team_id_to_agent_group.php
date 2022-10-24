<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHandeskTeamIdToAgentGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_group', function (Blueprint $table) {
            $table->integer('handesk_team_id')->unsigned()->nullable()->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_group', function (Blueprint $table) {
            $table->dropColumn('handesk_team_id');
        });
    }
}

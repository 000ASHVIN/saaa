<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkMailStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_mail_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->enum('type', ['click', 'open']);
            $table->string('ip');
            $table->string('url')->nullable()->default(null);
            $table->string('campaign_name');
            $table->integer('campaign_id')->unsigned()->index();
            $table->text('extra');
            $table->integer('count')->unsigned()->default(1);
            $table->timestamps();
            $table->unique(['email', 'type', 'campaign_id'], 'unique_email_and_type_for_campaign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bulk_mail_stats');
    }
}

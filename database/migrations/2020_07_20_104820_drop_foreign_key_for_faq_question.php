<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeyForFaqQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faq_questions', function (Blueprint $table) {
            $table->dropForeign('faq_questions_faq_tag_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faq_questions', function (Blueprint $table) {
            $table->foreign('faq_tag_id')->references('id')->on('faq_tags')->onDelete('cascade');
        });
    }
}

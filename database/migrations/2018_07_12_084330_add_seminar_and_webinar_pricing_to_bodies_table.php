<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeminarAndWebinarPricingToBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bodies', function (Blueprint $table) {
            $table->bigInteger('seminar')->default(0);
            $table->bigInteger('webinar')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bodies', function (Blueprint $table) {
            $table->dropColumn('seminar');
            $table->dropColumn('webinar');
        });
    }
}

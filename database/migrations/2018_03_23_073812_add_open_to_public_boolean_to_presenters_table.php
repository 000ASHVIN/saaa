<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpenToPublicBooleanToPresentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presenters', function (Blueprint $table) {
            $table->boolean('hidden')->default(false);
            $table->integer('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presenters', function (Blueprint $table) {
            $table->dropColumn('hidden');
            $table->dropColumn('position');
        });
    }
}

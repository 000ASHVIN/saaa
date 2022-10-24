<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstructionsSecretAndNameColumnsToStoreLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_links', function (Blueprint $table) {
            $table->text('name');
            $table->text('instructions')->nullable();
            $table->string('secret')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_links', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('instructions');
            $table->dropColumn('secret');
        });
    }
}

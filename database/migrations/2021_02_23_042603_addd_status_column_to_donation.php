<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdddStatusColumnToDonation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation', function($table) {
            $table->tinyInteger('status')->default(0)->index();
            $table->string('transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation', function($table) {
            $table->dropColumn('status');
            $table->dropColumn('transaction_id');
        });
    }
}

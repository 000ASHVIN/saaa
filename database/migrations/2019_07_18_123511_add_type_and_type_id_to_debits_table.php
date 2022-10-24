<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndTypeIdToDebitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debits', function (Blueprint $table) {
            $table->integer('type_id')->after('batch_id')->default(0);
            $table->enum('type',['course','subscription'])->after('type_id')->default('subscription');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('debits', function (Blueprint $table) {
            $table->dropColumn('type_id');
            $table->dropColumn('type');
        });
    }
}

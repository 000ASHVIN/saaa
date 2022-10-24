<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageTypeToPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('plans', function (Blueprint $table) {
            $table->string('package_type')->nullable();
        });
    }

    /**
 * Reverse the migrations. 
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('package_type');
        });
    }
}

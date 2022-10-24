<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtractReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extract_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('report');
            $table->integer('user_id');
            $table->string('request');
            $table->boolean('processed')->default(false);
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('extract_reports');
    }
}

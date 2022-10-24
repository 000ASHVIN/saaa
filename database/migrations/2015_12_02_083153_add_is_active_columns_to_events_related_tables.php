<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddIsActiveColumnsToEventsRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
        Schema::table('venues', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
        Schema::table('dates', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
        Schema::table('pricings', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        Schema::table('dates', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        Schema::table('pricings', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeValueTypeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `settings` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `settings` CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    }
}

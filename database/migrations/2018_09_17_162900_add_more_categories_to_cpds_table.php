<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreCategoriesToCpdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE cpds CHANGE COLUMN category category ENUM('accounting', 'tax', 'ethics', 'auditing', 'practice_management', 'reporting', 'legislation', 'technology') NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE cpds CHANGE COLUMN category category ENUM('accounting', 'tax', 'ethics', 'auditing', 'practice_management', 'reporting', 'legislation', 'technology') NULL");
    }
}

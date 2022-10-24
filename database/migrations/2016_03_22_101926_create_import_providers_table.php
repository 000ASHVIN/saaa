<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('model');
            $table->string('view');
            $table->string('menu_text');
            $table->json('validation_rules');
            $table->boolean('is_active')->default(true);
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
        Schema::drop('import_providers');
    }
}

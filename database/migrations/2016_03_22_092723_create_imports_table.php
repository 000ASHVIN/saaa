<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('import_provider_id')->unsigned()->index();
            $table->string('title');
            $table->text('description');
            $table->json('data');
            $table->integer('imported_count')->default(0);
            $table->json('imported');
            $table->integer('existing_count')->default(0);
            $table->json('existing');
            $table->integer('invalid_count')->default(0);
            $table->json('invalid');
            $table->integer('duplicates_count')->default(0);
            $table->json('duplicates');
            $table->boolean('completed_successfully')->default(false);
            $table->text('error');
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
        Schema::drop('imports');
    }
}

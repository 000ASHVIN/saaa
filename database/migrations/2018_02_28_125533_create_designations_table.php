<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->integer('body_id')->unsigned();
            $table->longText('description')->nullable();
            $table->enum('interval', ['monthly', 'yearly'])->nullable();

            $table->double('tax', 8,2)->nullable();
            $table->double('ethics', 8,2)->nullable();
            $table->double('auditing', 8,2)->nullable();
            $table->double('verifiable', 8,2)->nullable();
            $table->double('accounting', 8,2)->nullable();
            $table->double('unstructed', 8,2)->nullable();
            $table->double('total_hours', 8,2)->nullable();
            $table->double('non_verifiable', 8,2)->nullable();

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
        Schema::drop('designations');
    }
}

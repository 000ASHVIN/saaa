<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->string('branch_code')->nullable();
            $table->longText('reason')->nullable();
            $table->integer('user_id');
            $table->string('batch_id')->nullable();
            $table->enum('status', ['paid', 'unpaid']);
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
        Schema::drop('debits');
    }
}

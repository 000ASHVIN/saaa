<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallmentConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('interval')->unsigned()->default(1);
            $table->enum('interval_unit', ['year', 'month', 'day', 'week'])->default('month');
            $table->integer('total_number')->unsigned()->default(12);
            $table->string('first_due_date')->nullable()->default(null);
            $table->integer('grace_period_days')->unsigned()->default(14);
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
        Schema::drop('installment_configs');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model')->unique()->index();
            $table->string('description');
            $table->boolean('is_active')->default(true);
            $table->enum('type', ['percentage', 'amount'])->default('percentage');
            $table->decimal('value', 10, 2)->default(0);
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
        Schema::drop('store_discounts');
    }
}

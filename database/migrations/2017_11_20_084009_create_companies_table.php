<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('city');
            $table->string('title');
            $table->string('email');
            $table->string('secret');
            $table->string('country');
            $table->string('province');
            $table->string('area_code');
            $table->string('company_vat');
            $table->longText('description');
            $table->string('address_line_two');
            $table->string('address_line_one');
            $table->integer('plan_id')->unsigned();
            $table->integer('user_id')->unsigned();
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
        Schema::drop('companies');
    }
}

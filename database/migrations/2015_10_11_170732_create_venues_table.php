<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVenuesTable extends Migration {

	public function up()
	{
		Schema::create('venues', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('address_line_one')->nullable();
			$table->string('address_line_two')->nullable();
			$table->string('city')->nullable();
			$table->string('province')->nullable();
			$table->string('country')->nullable();
			$table->string('area_code')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('venues');
	}
}
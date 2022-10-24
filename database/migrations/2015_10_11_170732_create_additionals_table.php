<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdditionalsTable extends Migration {

	public function up()
	{
		Schema::create('additionals', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index();
			$table->string('name');
			$table->decimal('price', 10,2);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('additionals');
	}
}
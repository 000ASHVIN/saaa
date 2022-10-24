<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricingsTable extends Migration {

	public function up()
	{
		Schema::create('pricings', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index();
			$table->integer('venue_id')->unsigned()->index();
			$table->string('name');
			$table->string('description')->nullable();
			$table->integer('day_count')->default(1);
			$table->decimal('price', 10,2);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('pricings');
	}
}
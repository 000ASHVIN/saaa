<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsTable extends Migration {

	public function up()
	{
		Schema::create('tickets', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('description')->nullable();
			$table->string('code');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('event_id')->unsigned()->index();
			$table->integer('venue_id')->unsigned()->index();
			$table->text('additionals')->nullable();
			$table->decimal('cost', 10,2);
			$table->enum('status', ['paid', 'unpaid', 'cancelled']);
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email');
			$table->string('company')->nullable();
			$table->timestamps();
			$table->enum('dietary', ['hindu', 'kosher', 'asian', 'vegetarian', 'diabetic'])->nullable();
		});
	}

	public function down()
	{
		Schema::drop('tickets');
	}
}
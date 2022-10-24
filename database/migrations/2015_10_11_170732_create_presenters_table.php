<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePresentersTable extends Migration {

	public function up()
	{
		Schema::create('presenters', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('title')->nullable();
			$table->string('company')->nullable();
			$table->text('bio')->nullable();
			$table->string('avatar')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('presenters');
	}
}
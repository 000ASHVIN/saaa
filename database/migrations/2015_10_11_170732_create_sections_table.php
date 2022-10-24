<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSectionsTable extends Migration {

	public function up()
	{
		Schema::create('sections', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index();
			$table->integer('order');
			$table->string('title');
			$table->text('body');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('sections');
	}
}
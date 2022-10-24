<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('venue_id')->references('id')->on('venues')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('pricings', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('pricings', function(Blueprint $table) {
			$table->foreign('venue_id')->references('id')->on('venues')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('sections', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('additionals', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_user_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_event_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_venue_id_foreign');
		});
		Schema::table('pricings', function(Blueprint $table) {
			$table->dropForeign('pricings_event_id_foreign');
		});
		Schema::table('pricings', function(Blueprint $table) {
			$table->dropForeign('pricings_venue_id_foreign');
		});
		Schema::table('sections', function(Blueprint $table) {
			$table->dropForeign('sections_event_id_foreign');
		});
		Schema::table('additionals', function(Blueprint $table) {
			$table->dropForeign('additionals_event_id_foreign');
		});
	}
}
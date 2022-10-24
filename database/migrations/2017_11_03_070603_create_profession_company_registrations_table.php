<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionCompanyRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_registration_professions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city');
            $table->string('cell');
            $table->string('email');
            $table->string('company');
            $table->string('country');
            $table->string('province');
            $table->string('area_code');
            $table->string('id_number');
            $table->string('employees');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('company_vat');
            $table->string('selected_plan');
            $table->string('address_line_one');
            $table->string('address_line_two');
            $table->string('alternative_cell');
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
        Schema::drop('company_registration_professions');
    }
}

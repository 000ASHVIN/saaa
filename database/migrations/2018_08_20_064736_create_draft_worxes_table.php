<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraftWorxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_worxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('company_trading_name');
            $table->string('physical_business_address');
            $table->string('vat_number');
            $table->string('first_name');
            $table->string('surname');
            $table->string('contact_number');
            $table->string('id_or_passport');
            $table->string('type_of_subscription');
            $table->string('professional_body');
            $table->string('number_of_licenses');
            $table->string('applies_to_you');
            $table->string('type_of_business');
            $table->boolean('quote');
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
        Schema::drop('draft_worxes');
    }
}

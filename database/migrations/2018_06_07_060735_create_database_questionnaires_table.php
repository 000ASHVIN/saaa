<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabaseQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('database_questionnaires', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('contact_number');
            $table->string('email');
            $table->string('age');
            $table->string('gender');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('income');
            $table->string('race')->nullable();
            $table->string('job_title')->nullable();
            $table->string('other_job_title')->nullable();
            $table->string('level_of_education')->nullable();
            $table->string('accounting_field')->nullable();
            $table->string('other_accounting_field')->nullable();
            $table->string('type_of_professional')->nullable();
            $table->string('other_accountant_type')->nullable();
            $table->string('staff_members_amount')->nullable();
            $table->string('staff_benefits')->nullable();
            $table->string('professional_indemnity')->nullable();
            $table->string('organisation_do_you_work')->nullable();
            $table->string('employer_offer_benefits')->nullable();
            $table->string('do_you_belong_to_a_professional_body')->nullable();
            $table->string('select_professional_body')->nullable();
            $table->string('other_professional_body')->nullable();
            $table->string('expand_practice_income')->nullable();
            $table->string('reduce_risk_products')->nullable();
            $table->boolean('benefits_of_discounts')->nullable();
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
        Schema::drop('database_questionnaires');
    }
}

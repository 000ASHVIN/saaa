<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsToSponsorFormSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sponsor_form_submissions', function (Blueprint $table) {
            $table->boolean('registered_professional_accountancy_body')->default(false);
            $table->string('professional_body_name')->nullable();
            $table->string('other_professional_body_name')->nullable();
            $table->boolean('do_you_adhere_to_a_code_of_conduct')->default(false);
            $table->boolean('are_your_cpd_hours_up_to_date')->default(false);
            $table->boolean('do_you_use_engagement_letters')->default(false);
            $table->boolean('latest_technical_knowledge_or_library')->default(false);
            $table->boolean('do_you_have_the_required_infrastructure')->default(false);
            $table->boolean('do_you_or_your_firm_perform_reviews_of_all_work')->default(false);
            $table->boolean('do_you_apply_relevant_auditing_and_assurance_standards')->default(false);
            $table->boolean('do_you_use_the_latest_technology_and_software')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sponsor_form_submissions', function (Blueprint $table) {
            $table->dropColumn('registered_professional_accountancy_body');
            $table->dropColumn('professional_body_name');
            $table->dropColumn('other_professional_body_name');
            $table->dropColumn('do_you_adhere_to_a_code_of_conduct');
            $table->dropColumn('are_your_cpd_hours_up_to_date');
            $table->dropColumn('do_you_use_engagement_letters');
            $table->dropColumn('latest_technical_knowledge_or_library');
            $table->dropColumn('do_you_have_the_required_infrastructure');
            $table->dropColumn('do_you_or_your_firm_perform_reviews_of_all_work');
            $table->dropColumn('do_you_apply_relevant_auditing_and_assurance_standards');
            $table->dropColumn('do_you_use_the_latest_technology_and_software');
        });
    }
}

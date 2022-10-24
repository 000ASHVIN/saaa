<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToSponsorFormSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sponsor_form_submissions', function (Blueprint $table) {
            $table->boolean('adviser_to_contact_me')->default(0);
            $table->boolean('being_a_referral_agent')->default(0);
            $table->dateTime('date_of_birth')->nullable();
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
            $table->dropColumn('adviser_to_contact_me');
            $table->dropColumn('being_a_referral_agent');
            $table->dropColumn('date_of_birth');
        });
    }
}

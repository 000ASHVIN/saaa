<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOfCourseAndSemesterFieldToCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('type_of_course');
            $table->string('no_of_semesters');
            $table->string('semester_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('type_of_course');
            $table->dropColumn('no_of_semesters');
            $table->dropColumn('semester_price');
        });
    }
}

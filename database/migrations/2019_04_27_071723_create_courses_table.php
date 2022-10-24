<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->integer('max_students');
            $table->date('start_date');
            $table->date('end_date');
            $table->bigInteger('monthly_enrollment_fee');
            $table->bigInteger('yearly_enrollment_fee');
            $table->bigInteger('recurring_monthly_fee');
            $table->bigInteger('recurring_yearly_fee');
            $table->longText('link');
            $table->string('language');

            $table->integer('monthly_plan_id')->nullable();
            $table->integer('yearly_plan_id')->nullable();
            $table->string('reference')->unique();

            $table->softDeletes();
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
        Schema::drop('courses');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstallmentsFieldsToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('invoice_id')->unsigned()->nullable()->default(null);
            $table->boolean('has_installments')->default(false);
            $table->integer('installments_interval')->unsigned()->nullable()->default(null);
            $table->enum('installments_interval_unit', ['year', 'month', 'day', 'week'])->nullable()->default(null);;
            $table->integer('installments_total_number')->nullable()->default(null);
            $table->dateTime('installments_next_due_date')->nullable()->default(null);
            $table->integer('installments_grace_period_days')->nullable()->default(null);
            $table->integer('installments_item_id')->unsigned()->nullable()->default(null);
            $table->boolean('is_overdue')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
            $table->dropColumn('has_installments');
            $table->dropColumn('installments_interval');
            $table->dropColumn('installments_interval_unit');
            $table->dropColumn('installments_total_number');
            $table->dropColumn('installments_next_due_date');
            $table->dropColumn('installments_grace_period_days');
            $table->dropColumn('installments_item_id');
            $table->dropColumn('is_overdue');
        });
    }
}

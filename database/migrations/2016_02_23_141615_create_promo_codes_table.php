<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->index()->unique();
            $table->enum('discount_type', ['percentage', 'amount'])->default('percentage');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->boolean('has_limited_uses')->default(false);
            $table->integer('remaining_uses')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('view_path')->nullable()->default(null);
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
        Schema::drop('promo_codes');
    }
}

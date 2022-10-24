<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_listings', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('category_id')->unsigned()->index();
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->text('description');
            $table->string('image_url');
            $table->decimal('from_price', 10, 2)->default(0);
            $table->boolean('has_all_products_option')->default(true);
            $table->string('all_products_option_label')->default('All items');
            $table->enum('discount_type', ['percentage', 'amount'])->default('percentage');
            $table->decimal('discount', 10, 2)->default(0);
            $table->date('published_at');
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
        Schema::drop('store_listings');
    }
}

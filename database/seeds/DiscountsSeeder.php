<?php

use App\Store\Discount;
use App\Store\Discounts\AllListingProductsDiscount;
use Illuminate\Database\Seeder;

class DiscountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discount::create([
            'model' => AllListingProductsDiscount::class,
            'title' => 'All products combo',
            'description' => 'When you purchase all products in this listing',
            'value' => 30
        ]);
    }
}

<?php

use App\Store\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingMethod::create([
            'title' => 'Local courier',
            'description' => '2 to 3 working days.',
            'price' => 150
        ]);
        ShippingMethod::create([
            'title' => 'Pick up from office',
            'description' => 'Arrange to pick it up from our offices.',
            'price' => 0
        ]);
    }
}

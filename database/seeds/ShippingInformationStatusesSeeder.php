<?php

use App\Store\ShippingInformationStatus;
use Illuminate\Database\Seeder;

class ShippingInformationStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['title' => 'Awaiting payment', 'description' => 'We are still waiting for your payment or proof of payment'],
            ['title' => 'Awaiting stock', 'description' => 'We are waiting for stock from your supplier'],
            ['title' => 'At office', 'description' => 'The product is at the office and being prepared to be picked up'],
            ['title' => 'En route', 'description' => 'The product has been picked up and is on its way to you'],
            ['title' => 'Delivered', 'description' => 'The product has been delivered'],
            ['title' => 'Picked up', 'description' => 'The product has been picked up'],
            ['title' => 'Cancelled', 'description' => 'The order has been cancelled']
        ];

        foreach ($statuses as $status) {
            ShippingInformationStatus::create(['title' => $status['title'], 'description' => $status['description']]);
        }
    }
}

<?php

use App\Subscriptions\Plan;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
	    	'name' => 'Free',
	        'description' => 'Free membership plan',
	        'price' => 0,
	        'interval' => 'monthly',
            'type' => 'accounting'
        ]);

        Plan::create([
	    	'name' => 'Monthly Accounting only',
	        'description' => 'Monthly Accounting only CPD Subscription',
	        'price' => 325,
	        'interval' => 'monthly',
            'type' => 'accounting'
        ]);

        Plan::create([
	    	'name' => 'Yearly Accounting only',
	        'description' => 'Yearly Accounting only CPD Subscription Plan',
	        'price' => 3600,
	        'interval' => 'yearly',
            'type' => 'accounting'
        ]);

        Plan::create([
            'name' => 'Monthly Accounting and Tax',
            'description' => 'Monthly Accounting and Tax CPD Subscription',
            'price' => 430,
            'interval' => 'monthly',
            'type' => 'accounting_and_tax'
        ]);

        Plan::create([
            'name' => 'Yearly Accounting and Tax',
            'description' => 'Yearly Accounting and Tax CPD Subscription Plan',
            'price' => 4555,
            'interval' => 'yearly',
            'type' => 'accounting_and_tax'
        ]);
    }
}

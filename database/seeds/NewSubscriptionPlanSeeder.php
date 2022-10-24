<?php

use Illuminate\Database\Seeder;
use App\Subscriptions\Models\Plan;

class NewSubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create new Plans
        Plan::create([
        	'name' => 'Independent Reviewer',
        	'description' => '',
        	'price' => '270',
        	'interval' => 'month',
        	'interval_count' => 1,
        	'sort_order' => 1
    	]);

        Plan::create([
        	'name' => 'Bookkeeper/Junior Accountant',
        	'description' => '',
        	'price' => '330',
        	'interval' => 'month',
        	'interval_count' => 1,
        	'sort_order' => 2
    	]);

    	Plan::create([
        	'name' => 'Accounting Officer',
        	'description' => '',
        	'price' => '445',
        	'interval' => 'month',
        	'interval_count' => 1,
        	'sort_order' => 3
    	]);

    	Plan::create([
        	'name' => 'Accounting',
        	'description' => '',
        	'price' => '0',
        	'interval' => 'month',
        	'interval_count' => 1,
        	'sort_order' => 3,
            'inactive' => true
    	]);

    	Plan::create([
        	'name' => 'Accounting and Tax',
        	'description' => '',
        	'price' => '0',
        	'interval' => 'month',
        	'interval_count' => 1,
        	'sort_order' => 3,
            'inactive' => true
    	]);
    }
}

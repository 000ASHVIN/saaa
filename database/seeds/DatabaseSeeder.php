<?php

use App\AppEvents\DietaryRequirement;
use App\Store\ShippingInformationStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

//        $this->call(NewSubscriptionPlanSeeder::class);
        $this->call(CreateBodySeeder::class);
//        $this->call(DepartmentSeeder::class);
//        $this->call(JobSeeder::class);
//        $this->call(PresenterSeeder::class);
//        $this->call(EventsTableSeeder::class);
//        $this->call(AdminSeeder::class);
//        $this->call(PlansTableSeeder::class);
//        $this->call(VenuesSeeder::class);
//        $this->call(CIPCUpdateSeeder::class);
//        $this->call(DietaryRequirementsTableSeeder::class);
//
//        Store seeds
//        $this->call(DiscountsSeeder::class);
//        $this->call(TagsSeeder::class);
//        $this->call(ShippingMethodsSeeder::class);
//        $this->call(ShippingInformationStatusesSeeder::class);
//        $this->call(CoSecWorkCourseSeeder::class);

        Model::reguard();
    }
}

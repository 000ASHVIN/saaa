<?php

use App\AppEvents\DietaryRequirement;
use Illuminate\Database\Seeder;

class DietaryRequirementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DietaryRequirement::create([
            'name' => 'None',
            'price' => 0.00
        ]);
        DietaryRequirement::create([
            'name' => 'Halaal',
            'price' => 350.00
        ]);
        DietaryRequirement::create([
            'name' => 'Kosher',
            'price' => 350.00
        ]);
        DietaryRequirement::create([
            'name' => 'Vegetarian'
        ]);
    }
}

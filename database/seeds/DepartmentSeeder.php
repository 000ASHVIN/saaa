<?php

use App\Departments;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departments::create([
            'title' => 'Accounts Department',
            'description' => 'Some Intro Text'
        ]);
        Departments::create([
            'title' => 'Sales Department',
            'description' => 'Some Intro Text'
        ]);
        Departments::create([
            'title' => 'CPD Department',
            'description' => 'Some Intro Text'
        ]);
        Departments::create([
            'title' => 'Events Department',
            'description' => 'Some Intro Text'
        ]);
        Departments::create([
            'title' => 'IT Department',
            'description' => 'Some Intro Text'
        ]);
    }
}

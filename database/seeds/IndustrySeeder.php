<?php

use App\Users\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            'Agriculture, Forestry, Fishing and Hunting' => 'Agriculture, Forestry, Fishing and Hunting',
            'Automotive' => 'Automotive',
            'Aviation' => 'Aviation',
            'Banking' => 'Banking',
            'Business & Professional Services' => 'Business & Professional Services',
            'Chemical' => 'Chemical',
            'Construction & Building' => 'Construction & Building',
            'Education and Training' => 'Education and Training',
            'Energy & utilities' => 'Energy & utilities',
            'Financial and Accounting Services' => 'Financial and Accounting Services',
            'Government Organization' => 'Government Organization',
            'Health and Welfare' => 'Health and Welfare',
            'Information and Communication Technologies' => 'Information and Communication Technologies',
            'Insurance' => 'Insurance',
            'Legal' => 'Legal',
            'Leisure and Hospitality' => 'Leisure and Hospitality',
            'Manufacturing and Engineering' => 'Manufacturing and Engineering',
            'Media, Advertising & Communications' => 'Media, Advertising & Communications',
            'Mining, Quarrying and Oil & Gas' => 'Mining, Quarrying and Oil & Gas',
            'NGOs, NPOs & Body Corporates' => 'NGOs, NPOs & Body Corporates',
            'Real Estate and Rental and Leasing' => 'Real Estate and Rental and Leasing',
            'Restaurant, Food & Beverages' => 'Restaurant, Food & Beverages',
            'Retail' => 'Retail',
            'Security' => 'Security',
            'Tourism & Events' => 'Tourism & Events',
            'Transportation, Logistics and Warehousing' => 'Transportation, Logistics and Warehousing',
            'Water, Waste & Sanitation' => 'Water, Waste & Sanitation',
            'Wholesale' => 'Wholesale'
            // 'Other' => 'Other'
        ];
        foreach($industries as $industry) {
            Industry::create([
                'title' => $industry
            ]);
        }

    }
}

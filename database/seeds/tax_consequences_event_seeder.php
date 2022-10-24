<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class tax_consequences_event_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $seminar = Event::create([
            'type' => 'seminar',
            'name' => '2016 Tax Consequences of ITR14 and IT14SD forms',
            'slug' => '2016-tax-consequences-of-itr14-and-it14sd-forms',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'SARS have made numerous changes to the ITR14 with effect from 18 April 2016. The disclosure required in the return has been increased, with a specific focus on international transactions, debt reduction and capital gains tax. ',
            'featured_image' => 'http://imageshack.com/a/img924/4364/65CNu8.jpg',
            'start_date' => new Carbon('27-09-2016'),
            'end_date' => new Carbon('06-10-2016'),
            'next_date' => new Carbon('27-09-2016'),
            'start_time' => new Carbon('27-09-2016 09:00'),
            'end_time' => new Carbon('27-09-2016 13:00'),
            'is_redirect' => '1',
            'subscription_event' => '1',
            'category' => 'tax',
            'redirect_url' => 'https://sait.site-ym.com/events/event_list.asp?show=&group=&start=8%2F17%2F2016&end=&view=&cid=16216',
            'published_at' => Carbon::now()
        ]);

        //Plans
        $seminar->plans()->attach([4, 5]); //Only Tax and Accounting

        $berg = Venue::create([
            'name' => 'Diep in die Berg',
            'address_line_one' => '929 Disselboom Street',
            'address_line_two' => '',
            'city' => 'Pretoria',
            'province' => 'Gauteng  ',
            'country' => 'South Africa',
            'area_code' => '0050',
        ]);
        $seminar->venues()->attach($berg);

        $jhb = Venue::create([
            'name' => 'Wanderers Club',
            'address_line_one' => '21 North Road',
            'address_line_two' => 'Illovo',
            'city' => 'Johannesburg',
            'province' => 'Gauteng  ',
            'country' => 'South Africa',
            'area_code' => '2169',
        ]);
        $seminar->venues()->attach($jhb);

        $beach = Venue::create([
            'name' => 'Beach Hotel',
            'address_line_one' => 'Marine Drive Summerstrand',
            'address_line_two' => '',
            'city' => 'Port Elizabeth',
            'province' => 'Eastern Cape ',
            'country' => 'South Africa',
            'area_code' => '6001',
        ]);
        $seminar->venues()->attach($beach);

        $premier = Venue::create([
            'name' => 'Premier Hotel OR Tambo',
            'address_line_one' => '73 Gladiator Street',
            'address_line_two' => '',
            'city' => 'Kempton Park',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '1631',
        ]);
        $seminar->venues()->attach($premier);

        $durban = Venue::create([
            'name' => 'Coastlands Umhlanga',
            'address_line_one' => '329 Umhlanga Rocks Drive',
            'address_line_two' => '',
            'city' => 'Durban',
            'province' => 'KwaZulu-Natal',
            'country' => 'South Africa',
            'area_code' => '4000',
        ]);
        $seminar->venues()->attach($durban);

        $capetown = Venue::create([
            'name' => 'Lord Charles Hotel',
            'address_line_one' => 'Cnr Main Road (M9) & Broadway Boulevard (R44)',
            'address_line_two' => '',
            'city' => 'Somerset West',
            'province' => 'Cape Town',
            'country' => 'South Africa',
            'area_code' => '7130',
        ]);
        $seminar->venues()->attach($capetown);

        $web = Venue::create([
            'name' => 'Online webinar / recording',
            'address_line_one' => 'Online',
            'address_line_two' => '',
            'city' => '',
            'province' => '',
            'country' => '',
            'area_code' => '',
            'type' => 'online'
        ]);
        $seminar->venues()->attach($web);
        Model::reguard();
    }
}

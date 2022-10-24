<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class vat_fundamental_event_seeder extends Seeder
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
            'name' => '2016 VAT Fundamentals',
            'slug' => 'vat_fundamentals',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'It\'s an undisputed fact that VAT is a deceptively complicated subject with many demons lurking in the details. It is therefore expected that SARS will increase their focus on compliance with transactional tax such as VAT.  To this end, SARS recently announced a focus on forensic audit methodology in driving tax compliance of high risk areas.',
            'featured_image' => 'http://imageshack.com/a/img922/7053/EGccDB.jpg',
            'start_date' => new Carbon('12-07-2016'),
            'end_date' => new Carbon('21-07-2016'),
            'next_date' => new Carbon('12-07-2016'),
            'start_time' => new Carbon('12-07-2016 09:00'),
            'end_time' => new Carbon('13-07-2016 09:00'),
            'is_redirect' => '1',
            'subscription_event' => '1',
            'category' => '',
            'redirect_url' => 'https://sait.site-ym.com/events/event_list.asp?show=&group=&start=5%2F31%2F2016&end=&view=&cid=15759',
            'published_at' => Carbon::now()
        ]);

        //Plans
        $seminar->plans()->attach([4, 5]); //Only Tax and Accounting
        $jhb = Venue::create([
            'name' => 'Wanderers Club',
            'address_line_one' => '21 North Road',
            'address_line_two' => 'Illovo',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '2169',
        ]);
        $seminar->venues()->attach($jhb);

        $dbn = Venue::create([
            'name' => 'Coastlands Umhlanga',
            'address_line_one' => '329 Umhlanga',
            'address_line_two' => 'Rocks Drive',
            'city' => 'Durban',
            'province' => 'KZN',
            'country' => 'South Africa',
            'area_code' => '4000',
        ]);
        $seminar->venues()->attach($dbn);

        $pt = Venue::create([
            'name' => 'Diep in die Berg',
            'address_line_one' => '929 Disselboom Street',
            'address_line_two' => '',
            'city' => 'Wapadrand',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '0050',
        ]);
        $seminar->venues()->attach($pt);

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

        $premier_hotel = Venue::create([
            'name' => 'Premier Hotel OR Tambo',
            'address_line_one' => '73 Gladiator Street',
            'address_line_two' => 'Rhodesfield',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '2520',
        ]);
        $seminar->venues()->attach($premier_hotel);

        $lord_charles = Venue::create([
            'name' => 'Lord Charles Hotel',
            'address_line_one' => 'Cnr Main Road (M9),',
            'address_line_two' => 'Broadway Boulevard (R44)',
            'city' => 'Somerset West',
            'province' => 'Western Cape',
            'country' => 'South Africa',
            'area_code' => '2520',
        ]);
        $seminar->venues()->attach($lord_charles);
        Model::reguard();
    }
}

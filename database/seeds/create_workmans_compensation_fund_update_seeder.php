<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class create_workmans_compensation_fund_update_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $webinar = Event::create([
            'type' => 'webinar',
            'name' => 'Workman’s Compensation Fund Update',
            'slug' => 'workmans-compensation-fund-update',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Workman\'s Compensation has been causing headaches for accountants and their clients for the last couple of years. Industry has requested that COID intervene and provide a speaker to update and interact with accountants to address current uncertainties. Join Marius Putter, Head of the COID Department at Company Partners for this two-hour webinar as we unpack the latest developments.',
            'featured_image' => 'http://imageshack.com/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('26-10-2016'),
            'end_date' => new Carbon('26-10-2016'),
            'next_date' => new Carbon('26-10-2016'),
            'start_time' => new Carbon('26-10-2016 14:00'),
            'end_time' => new Carbon('26-10-2016 16:00'),
            'subscription_event' => '1',
            'category' => 'all_cpd_subs',
            'published_at' => Carbon::now()
        ]);

        //Plans
        $webinar->plans()->attach([2, 3, 4, 5]); // CPD SUBS

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
        $web->dates()->save(new Date([
            'date' => new Carbon('26-10-2016')
        ]));
        $webinar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $webinar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 425,
            'cpd_hours' => 2
        ]);
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);
        Model::reguard();
    }
}

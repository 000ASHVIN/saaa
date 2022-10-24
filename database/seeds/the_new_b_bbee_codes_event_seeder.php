<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class the_new_b_bbee_codes_event_seeder extends Seeder
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
            'name' => 'The new B-BBEE Codes: 2016 update',
            'slug' => 'the_new_b_bbee_codes',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => '/assets/frontend/images/events/2016/amendments-to-the-ifrs-for-smes.jpg',
            'start_date' => new Carbon('27-05-2016'),
            'end_date' => new Carbon('27-05-2016'),
            'next_date' => new Carbon('27-05-2016'),
            'start_time' => new Carbon('27-05-2016 11:00'),
            'end_time' => new Carbon('27-05-2016 13:00'),
            'short_description' => 'The amended Codes of Good Practice for Broad-based Black Economic Empowerment (B-BBEE) came into effect on 1 May 2015. These new Codes continue to cause major uncertainty amongst accountants advising clients on B-BBEE transactions and issuing EME certificates.  Join this two-hour webinar and  get an update on the new Codes and how they will affect you, your practice and your clients.  ',
            'published_at' => Carbon::now()
        ]);

        //Plans
        $webinar->plans()->attach([2, 3, 4, 5]); //all cpd subs

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
            'date' => new Carbon('27-05-2016')
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

<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class create_apps_for_accountants_webinar_event_seeder extends Seeder
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
            'name' => 'Apps for accountants and their clients for business growth',
            'slug' => 'apps-for-accountants-and-their-clients-for-business-growth',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Colin has over 12 years of experience in the accounting profession and is widely considered an expert in cloud accounting software implementation, development, integration and best practice.',
            'featured_image' => 'http://imageshack.com/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('18-08-2016'),
            'end_date' => new Carbon('18-08-2016'),
            'next_date' => new Carbon('18-08-2016'),
            'start_time' => new Carbon('18-08-2016 14:00'),
            'end_time' => new Carbon('18-08-2016 16:00'),
            'published_at' => Carbon::now(),
            'subscription_event' => true,
            'category' => 'accounting'
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
            'date' => new Carbon('18-08-2016')
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

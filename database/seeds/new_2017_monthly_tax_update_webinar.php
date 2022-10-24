<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class new_2017_monthly_tax_update_webinar extends Seeder
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
            'type' => 'webinar',
            'name' => '2017 Monthly Tax Update Webinar',
            'slug' => '2017-monthly-tax-update-webinar',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Tax law is arguably one of the most dynamic and complex fields of law one can practice in. Due to legislative amendments and case law, it is an ever changing environment.',
            'subscription_event' => '',
            'category' => '',
            'is_redirect' => true,
            'redirect_url' => 'http://www.thesait.org.za/events/EventDetails.aspx?id=917492&group=',
            'featured_image' => 'http://imagizer.imageshack.us/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('31-01-2017'),
            'end_date' => new Carbon('31-01-2017'),
            'next_date' => new Carbon('31-01-2017'),
            'start_time' => new Carbon('31-01-2017 15:00'),
            'end_time' => new Carbon('31-01-2017 17:00'),
            'published_at' => Carbon::now()
        ]);

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
            'date' => new Carbon('31-01-2017')
        ]));
        $seminar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 450,
            'cpd_hours' => 2
        ]);
        $price->features()->sync([18]);

        Model::reguard();
    }
}

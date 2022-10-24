<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class cash_flow_event_seeder extends Seeder
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
            'name' => 'Cash-flow Forecasting - getting to grips with cash-flow and liquidity risk management',
            'slug' => 'cash-flow-forecasting-getting-to-grips-with-cash-flow-and-liquidity-risk-management',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Cash is the truth, it cannot be fudged, it is either there or it is not there. This is very different from accrual accounting. It is important for finance professionals and accounting practitioners to understand the importance of their analysis and decisions as it relates to cash in their or their clients’ organisations. ',
            'featured_image' => 'http://imagizer.imageshack.us/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('27-07-2016'),
            'end_date' => new Carbon('27-07-2016'),
            'next_date' => new Carbon('27-07-2016'),
            'start_time' => new Carbon('27-07-2016 14:00'),
            'end_time' => new Carbon('27-07-2016 16:00'),
            'subscription_event' => true,
            'category' => 'accounting',
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
            'date' => new Carbon('27-07-2016')
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

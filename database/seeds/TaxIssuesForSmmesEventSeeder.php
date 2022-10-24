<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TaxIssuesForSmmesEventSeeder extends Seeder
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
            'name' => 'Tax Issues for SMME',
            'slug' => 'tax-issues-for-smme',
            'description' => '<p> Please see SAIT Website </p>',
            'short_description' => 'Small, Medium and Micro-sized Enterprises (SMMEs) may be relatively small, but ‘small’ certainly does not mean ‘simple’ when it comes to tax matters. Tax legislation is complex and small business owners have substantial tax risks and thus making mistakes can be extremely costly. Additionally, the Davis Tax Committee is still completing its work on the limited tax relief that is available to SMMEs.',
            'featured_image' => '/assets/frontend/images/events/2016/monthly-tax-technical-webinar.jpg',
            'start_date' => new Carbon('11-04-2016'),
            'end_date' => new Carbon('11-04-2016'),
            'next_date' => new Carbon('11-04-2016'),
            'start_time' => new Carbon('11-04-2016 15:00'),
            'end_time' => new Carbon('11-04-2016 17:00'),
            'published_at' => Carbon::now(),
            'is_redirect' => '1',
            'redirect_url' => 'http://www.thesait.org.za/events/event_list.asp?show=&group=&start=2%2F17%2F2016&end=&view=&cid=15413',
            'is_active' => '1',
            'is_open_to_public' => '0'
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
            'date' => new Carbon('11-04-2016')
        ]));
        $webinar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $webinar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 450,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);
        Model::reguard();
    }
}

<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class double_your_income_event_seeder extends Seeder
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
            'name' => 'Double Your Income',
            'slug' => 'double_your_income',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => 'http://imageshack.com/a/img921/5117/KwP2kx.jpg',
            'start_date' => new Carbon('29-07-2016'),
            'end_date' => new Carbon('29-07-2016'),
            'next_date' => new Carbon('29-07-2016'),
            'start_time' => new Carbon('29-07-2016 09:00'),
            'end_time' => new Carbon('29-07-2016 17:00'),
            'short_description' => 'This seminar focuses on laying the foundations that will enable you to maximise your profitability potential. It requires you to look carefully at approaching your business differently than perhaps you have done so in the past. You are managing a business and need to pay maximum attention to your best client – your own firm. Your business needs to have the care and attention you afford those clients who you do regard as your best clients.',
            'published_at' => Carbon::now(),
            'subscription_event' => false,
            'category' => 'accounting',
        ]);

        //Plans
        //$seminar->plans()->attach([2, 3, 4, 5]); //all cpd subs

        //Venues, pricing and Dates
        $half_day = Venue::create([
            'name' => 'Half Day Seminars',
            'address_line_one' => '21 North St',
            'address_line_two' => '',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '2196'
        ]);

        $full_day = Venue::create([
            'name' => 'Full Day Seminars',
            'address_line_one' => '21 North St',
            'address_line_two' => '',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '2196'
        ]);

        $combined = Venue::create([
            'name' => 'Combined Seminars',
            'address_line_one' => '21 North St',
            'address_line_two' => '',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '2196'
        ]);

        $half_day->dates()->save(new Date([
            'date' => new Carbon('28-07-2016')
        ]));

        $full_day->dates()->save(new Date([
            'date' => new Carbon('29-07-2016')
        ]));

        $combined->dates()->save(new Date([
            'date' => new Carbon('28-07-2016')
        ]));

        $combined->dates()->save(new Date([
            'date' => new Carbon('29-07-2016')
        ]));

        $seminar->venues()->attach($combined);        
        $seminar->venues()->attach($full_day);
        $seminar->venues()->attach($half_day);

        // Better Billing. Better Collections. Lower Lock Up. Guaranteed
        Pricing::create(['event_id' => $seminar->id,'venue_id' => $half_day->id,'name' => 'Better Billing. Better Collections. Lower Lock Up. Guaranteed (Only)','description' => 'Better Billing. Better Collections. Lower Lock Up. Guaranteed.','day_count' => 1,'price' => 1199,'cpd_hours' => 4]);

        Pricing::create(['event_id' => $seminar->id,'venue_id' => $half_day->id,'name' => 'Delivering Outstanding Client Service (Only)','description' => 'Delivering Outstanding Client Service','day_count' => 1,'price' => 1199,'cpd_hours' => 4]);

        Pricing::create(['event_id' => $seminar->id,'venue_id' => $full_day->id,'name' => 'Double Your Income full day Seminar (Only)','description' => 'Double Your Income full day Seminar','day_count' => 1,'price' => 2199,'cpd_hours' => 8]);

        // Combined        
        Pricing::create(['event_id' => $seminar->id,'venue_id' => $half_day->id,'name' => 'Better Billing and Client Service (SAVE R200!)','description' => 'Better Billing and Client Service','day_count' => 1,'price' => 2198,'cpd_hours' => 8]);
  
        Pricing::create(['event_id' => $seminar->id,'venue_id' => $combined->id,'name' => 'Double Your Income full day Seminar and Better Billing (SAVE R300!)','description' => 'Double Your Income full day Seminar and Better Billing','day_count' => 2,'price' => 3108,'cpd_hours' => 12]);

        Pricing::create(['event_id' => $seminar->id,'venue_id' => $combined->id,'name' => 'Double Your Income full day Seminar and Client Service (SAVE R300!)','description' => 'Double Your Income full day Seminar and Client Service','day_count' => 2,'price' => 3108,'cpd_hours' => 12]);

        Pricing::create(['event_id' => $seminar->id,'venue_id' => $combined->id,'name' => 'Both half day seminars and Double Your Income full day Seminar (SAVE R500!)','description' => 'Double Your Income full day Seminar','day_count' => 2,'price' => 3897,'cpd_hours' => 16]);

        //Extras
        $notes1 = Extra::create(['name' => 'Client Service notes', 'price' => 100.00]);
        $notes2 = Extra::create(['name' => 'Better Billing notes', 'price' => 100.00]);
        $notes3 = Extra::create(['name' => 'Double Your Income notes', 'price' => 100.00]);

        $seminar->extras()->save($notes1);
        $seminar->extras()->save($notes2);
        $seminar->extras()->save($notes3);
        Model::reguard();
    }
}

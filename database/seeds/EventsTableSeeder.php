<?php

use Carbon\Carbon;
use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Venue;
use App\AppEvents\Pricing;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    public function run()
    {
//        $date = 7;
//        $reps = 0;
//
//        // Create Event
//        for ($i = 0; $i < 10; $i++) {
//
//            $event = factory(Event::class)->create([
//                'start_date' => Carbon::now()->addDays(7 + $i),
//                'end_date' => Carbon::now()->addDays(15 + $i),
//                'next_date' => Carbon::now()->addDays(7 + $i),
//                'published_at' => Carbon::now()->subDays(1)
//            ]);
//
//            for ($j = 1; $j <= 3; $j++) {
//
//                $venue = factory(Venue::class)->create();
//
//                $event->venues()->attach($venue);
//
//                for ($k = 1; $k <= 3; $k++) {
//                    factory(Date::class)->create([
//                        'venue_id' => $venue->id,
//                        'date' => Carbon::now()->addDays($date + $k + $reps)
//                    ]);
//                }
//
//                $reps += 3;
//
//                for ($l = 1; $l <= 3; $l++) {
//                    factory(Pricing::class)->create([
//                        'event_id' => $event->id,
//                        'venue_id' => $venue->id,
//                        'name' => $l . ' Day Pass',
//                        'description' => $l . ' Day pass to event',
//                        'day_count' => $l,
//                        'price' => 199 * $l,
//                    ]);
//                }
//
//            }
//        }

        $this->initialEvents();
    }

    private function initialEvents()
    {
        //Helping your clients gain access to finance

        $webinar = new Event([
            'type' => 'webinar',
            'name' => 'Helping your clients gain access to finance',
            'slug' => 'helping-your-clients-gain-access-to-finance',
            'description' => '<div class="heading-title heading-dotted" style="margin-bottom: 10px; margin-top: 20px">
    <h3>Overview:</h3></div>
Despite their contribution to employment and productivity, the needs of South Africa’s small business community are still not being adequately met by policy makers. Ensuring that these SMEs have adequate access to finance to allow them to invest, grow and innovate is absolutely critical. Access to finance, however, remains a significant concern for many small businesses. As an accountant, you can play a significant role in helping your SME clients to secure the funding they need, and at the same time create a new, value-added service to your offering. This two-hour webinar will explain the benefits of helping your clients gain access to finance and give you a practical overview of the steps you need to take to offer this service.
<hr>
<div class="heading-title heading-dotted" style="margin-bottom: 10px; margin-top: 20px">
    <h3>Content:</h3></div>
<ul>
    <li>The link between the accountant and a finance house</li>
    <li>How to know what products will suit your clients’ needs?</li>
    <li>The difference between referring business to a bank versus a private finance house</li>
    <li>Who can become a broker/what are the benefits of becoming a broker?</li>
    <li>What do you need to provide to a finance house to facilitate a transaction?</li>
    <li>A brief overview of Merchant West</li>
</ul>
<hr>
<div class="heading-title heading-dotted" style="margin-bottom: 10px; margin-top: 20px">
    <h3>Presenters:</h3></div>
<p><strong>Mark Maiden, Director: Working Capital Solutions, Merchant West</strong></p>
<p>Mark brings 10 years of corporate banking experience, having been employed with NBS Corporate, BOE Corporate Bank and Nedbank from 1995 to 2004. Mark has extensive knowledge of both moveable and immoveable asset finance, together with working capital solutions and cash flow solutions. Mark operated a financial consulting business for three years, where he specialised in business and financial strategies, working capital solutions, asset finance solutions and cash flow management. Mark was appointed a director at Merchant West in March 2008 and heads up the Working Capital Solutions business.</p>
<p><strong>Angela Craul, Head of Brokers & Regions, Merchant West</strong></p>
<p>Angela studied at the Nelson Mandela Metropolitan University (UPE) and started working at Standard Bank Insurance Services in 2001 as a Sales Consultant. In 2002, she got promoted to Regional Sales Team Leader for the North West, Mpumalanga and Limpopo Provinces SBIS, with a focus on sales of Insurance products associated with Vehicle and Asset Finance. In 2006, she was promoted to Portfolio Manager of Standard Bank Vehicle and Asset Finance, Business Banking. Angela joined Merchant West in 2013 as Sales Consultant within the Commercial Asset Finance Division and was promoted to Head of Brokers & Regions in 2014.</p>
<hr>
<div class="heading-title heading-dotted" style="margin-bottom: 10px; margin-top: 20px">
    <h3>CPD:</h3></div>
<p>Attendance of this webinar will accrue 2 hours’ CPD for members of a relevant professional body such as ACCA, SAICA, AAT, SAIPA, SAIBA, IAC, CSSA, ICBA, LSSA, FPI, and the IBA. ACCA is a full member of IFAC. The ACCA CPD policy is compliant with IFAC IES7 and is recognised by SAICA, AAT, SAIPA, SAIBA, IAC, CIS and others.</p>
',
            'featured_image' => '/assets/frontend/images/events/helping-your-clients-gain-access-to-finance.jpg',
            'start_date' => new Carbon('19-11-2015'),
            'end_date' => new Carbon('19-11-2015'),
            'next_date' => new Carbon('19-11-2015'),
            'start_time' => new Carbon('19-11-2015 14:00'),
            'end_time' => new Carbon('19-11-2015 16:00'),
            'published_at' => Carbon::now()
        ]);

        $webinar->save();

        $onlineVenue = new Venue([
            'name' => 'Online'
        ]);

        $onlineVenue->save();

        $onlineVenue->dates()->save(new Date([
            'date' => new Carbon('19-11-2015')
        ]));

        $webinar->venues()->attach($onlineVenue);

        $price = new Pricing([
            'event_id' => $webinar->id,
            'venue_id' => $onlineVenue->id,
            'name' => 'Access Pass',
            'description' => 'Access pass for webinar',
            'day_count' => 1,
            'price' => 399.00
        ]);

        $price->save();

        //Preparing Working Papers for Small Audits and IR Engagements

        $seminar = new Event([
            'type' => 'seminar',
            'name' => 'Preparing Working Papers for Small Audits and IR Engagements',
            'slug' => 'preparing-working-papers-for-small-audits-and-ir-engagements',
            'description' => '',
            'featured_image' => '/assets/frontend/images/events/preparing-working-papers-for-small-audits-and-ir-engagements.jpg',
            'start_date' => new Carbon('16-11-2015'),
            'end_date' => new Carbon('25-11-2015'),
            'next_date' => new Carbon('16-11-2015'),
            'start_time' => new Carbon('16-11-2015 09:00'),
            'end_time' => new Carbon('16-11-2015 17:00'),
            'is_redirect' => true,
            'redirect_url' => 'http://www.accountingacademy.co.za/events/event_details.asp?id=684903',
            'published_at' => Carbon::now()
        ]);

        $seminar->save();

        $jhb = new Venue(['name' => 'Johannesburg']);
        $jhb->save();
        $jhb->dates()->save(new Date([
            'date' => new Carbon('16-11-2015')
        ]));
        $seminar->venues()->attach($jhb);

        $pta = new Venue(['name' => 'Pretoria']);
        $pta->save();
        $pta->dates()->save(new Date([
            'date' => new Carbon('17-11-2015')
        ]));
        $seminar->venues()->attach($pta);

        $web = new Venue(['name' => 'Live Webinar']);
        $web->save();
        $web->dates()->save(new Date([
            'date' => new Carbon('17-11-2015')
        ]));
        $seminar->venues()->attach($web);

        $dbn = new Venue(['name' => 'Durban']);
        $dbn->save();
        $dbn->dates()->save(new Date([
            'date' => new Carbon('25-11-2015')
        ]));
        $seminar->venues()->attach($dbn);
    }
}

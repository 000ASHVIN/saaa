<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\EventRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class AdminEventPromoCodeController extends Controller
{
    private $eventRepository;
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function index()
    {
        $events = $this->eventRepository->findEventsWithPromoCodes();
        return view('admin.event.promo.index', compact('events'));
    }

    public function download()
    {
        $users = \DB::table('coupon_users')->get();

        $file = Excel::create('User Coupons', function ($excel) use ($users) {
            $excel->sheet('Coupons', function ($sheet) use ($users) {
                $sheet->appendRow(['Date', 'Status', 'Reference', 'Description', 'Amount']);
                $sheet->appendRow([
                    'User Id',
                    'First Name',
                    'Last Name',
                    'Email',
                    'Package',
                    'Code',
                    'Event',
                    'Venue Type',
                ]);

                foreach ($users as $user) {
                    $sheet->appendRow([
                        $user->user_id,
                        $user->first_name,
                        $user->last_name,
                        $user->email_address,
                        $user->package,
                        $user->code,
                        $user->event_name,
                        $user->type,
                    ]);
                }
            });

        })->download('xls');
    }
}

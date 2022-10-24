<?php

namespace App\Repositories\Donation;

use App\Billing\Invoice;
use App\Users\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Knp\Snappy\Pdf;
use App\Donation;
use App\Jobs\SendDonationConfirmation;
use App\Jobs\SendDonationDetails;
use Illuminate\Foundation\Bus\DispatchesJobs;

class DonationRepository
{
    use DispatchesJobs;

    public function createForUserAndNotify($user, $donations) {
        $donation = Donation::createForUser($user, $donations);
        $donation->status = '1';
        $donation->save();
        
        $this->notifyUser($donation);
        $this->notifyStaff($donation);
        return $donation;
    }

    public function notifyUser($donation) {

        if(env('APP_THEME') != 'taxfaculty') {
            return false;
        }

        $this->dispatch(new SendDonationConfirmation($donation));
        return true;
    }

    public function sendConfirmation($donation) {

        if(env('APP_THEME') != 'taxfaculty') {
            return false;
        }

        try{
            if($donation){

                $pdf = App::make('snappy.pdf.wrapper');
                $pdf->loadView('emails.donation.certificate', ['donation' => $donation]);

                $location = public_path('assets/frontend/donation/certificate-' . $donation->id . '.pdf');
                if(file_exists($location)) {
                    File::delete($location);
                }
                $pdf->save($location);
                
                $res = Mail::send('emails.donation.confirmation', ['donation' => $donation], function ($message) use ($donation, $location) {
                    $message->from(config('app.email'), config('app.name'));
                    $message->to($donation->email);

                    $message->subject('Thanks for your support: S18A certificate attached');
                    $message->attach($location, [
                        'as' => 'S18A certificate.pdf'
                    ]);
                });
                File::delete($location);

            }
        }catch (\Exception $exception){
            \Log::info('Exception when sending donation certificate.');
            \Log::info($exception);
        }

    }

    public function notifyStaff($donation) {

        if(env('APP_THEME') != 'taxfaculty') {
            return false;
        }

        $this->dispatch(new SendDonationDetails($donation));
        return true;
    }

    public function sendDetailsToStaff($donation) {

        if(env('APP_THEME') != 'taxfaculty') {
            return false;
        }

        try{
            if($donation){

                $to = env('DONATION_MAIL');
                Mail::send('emails.donation', ['donation' => $donation], function ($m) use ($donation, $to) {
                    $m->from(config('app.email'), config('app.name'));
                    $m->to($to, 'Donation')->subject('Donation Details');
                });

            }
        }catch (\Exception $exception){
            \Log::info('Exception when sending donation certificate.');
            \Log::info($exception);
        }

    }

}
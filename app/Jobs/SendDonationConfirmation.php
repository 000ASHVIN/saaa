<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Repositories\Donation\DonationRepository;
use App\Donation;

class SendDonationConfirmation extends Job implements SelfHandling
{
    protected $donation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DonationRepository $donationRepository)
    {
        $donationRepository->sendConfirmation($this->donation);
    }
}

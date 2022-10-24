<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Users\User;
use App\Users\UserRepository;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateUserAccount extends Job implements SelfHandling
{

    public $first_name;

    public $last_name;

    public $email;

    public $password;

    public $cell;

    public $id_numer;

    private $alternative_cell;

    private $membership_number;

    private $specified_body;

    private $interest;

    private $employment;

    private $industry;

    /**
     * Create a new job instance.
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $password
     * @param $cell
     * @param $id_number
     * @param $alternative_cell
     * @param $membership_number
     * @internal param $specified_body
     * @internal param $alternativeCell
     */
    public function __construct($first_name, $last_name, $email, $password, $cell, $id_number, $alternative_cell, $membership_number, $specified_body, $interest, $employment, $industry)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
        $this->cell = $cell;
        $this->id_numer = $id_number;
        $this->alternative_cell = $alternative_cell;
        $this->membership_number = $membership_number;
        $this->specified_body = $specified_body;
        $this->interest = $interest;
        $this->employment = $employment;
        $this->industry = $industry;
    }

    /**
     * Execute the job.
     *
     * @param User $user
     * @param UserRepository $userRepository
     * @return User
     */
    public function handle(User $user, UserRepository $userRepository)
    {
        $user = $user->register($this->first_name, $this->last_name, $this->email, $this->password, $this->cell, $this->id_numer, $this->alternative_cell, $this->membership_number, $this->specified_body, $this->interest, $this->employment, $this->industry);
        $userRepository->save($user);
        $userRepository->assignSettings($user);
        $userRepository->createProfileFor($user);
        $userRepository->createWalletForUser($user);
        return $user;
    }
}

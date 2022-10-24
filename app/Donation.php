<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{

    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'donation';
	
	protected $fillable = ['first_name','last_name','email','company_name','cell','address','amount','paymentOption', 'status', 'transaction_id'];

    public static function createForUser($user, $donations) {

        $donate = null;

        if( $user && $donations ) {

            $address = $user->getAddress("billing");
            $address_text = '';
            if($address) {
                $address_text .= $address->line_one.', ';
                $address_text .= $address->line_two.', ';
                $address_text .= $address->city.', ';
                $address_text .= $address->province.', ';
                $address_text .= $address->country.' ';
                $address_text .= $address->area_code;
            }

            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'company_name' => $user->profile?$user->profile->company:'',
                'cell' => $user->cell,
                'paymentOption' => request()->paymentOption,
                'address' => $address_text,
                'amount' => $donations
            ];
            $donate = Donation::create($data);
        }

        return $donate;

    }

  
}

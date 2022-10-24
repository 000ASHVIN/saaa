<?php

use App\AppEvents\PromoCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class NPOConferencePromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        PromoCode::create([
            'code' => 'SAAANPOCONF16LD9S7Q',
            'discount_type' => 'percentage',
            'discount_amount' => 15.00,
            'view_path' => 'events.promo-codes.npo'
        ]);
        Model::reguard();
    }
}

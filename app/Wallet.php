<?php

namespace App;

use App\Billing\Invoice;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $fillable = ['amount', 'reference', 'invoice_reference', 'invoice_order_refference'];


    /*
     * Set the reference automatically for the new wallet
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            $model->reference = '#'.str_random(8);
        });
    }

    /*
     * Get the amount attribute
     */
    public function getAmountAttribute()
    {
        return $this->attributes['amount'] / 100;
    }

    /**
     * Set the amount attribute
     * @param $amount
     * @return
     */
    public function setAmountAttribute($amount)
    {
        return $this->attributes['amount'] = $amount * 100;
    }

    /**
     * Adding funds to your wallet.
     * If charge was successful return true, else false.
     * @param $amount
     * @param $method
     * @param null $date
     * @param null $Invreff
     * @param null $OrderReff
     * @return string
     */
    public function add($amount, $method, $date = null, $Invreff = null, $OrderReff = null)
    {
        if (! $date){
            $date = Carbon::now();
        }

        // Find the user..
        $user = User::find($this->user_id);

        try{
            $available = $this->amount + $amount;
            $this->transactions()->create([
                'created_at' => $date,
                'type' => 'credit',
                'category' => 'payment',
                'method' => $method,
                'amount' => $amount,
                'invoice_reference' => ($Invreff ? $Invreff : "-"),
                'invoice_order_refference' => ($OrderReff ? $OrderReff : "-")
            ]);
            $this->update(['amount' => $available]);

            try{
                $this->sendEmail($user, 'emails.wallet.successful', 'Your Topup was successful');
            }catch (Exception $exception){
                return;
            }
            return 'success';
        }catch (Exception $exception){
            return 'failed';
        }
    }

    /**
     * @param $amount
     * @param Invoice $invoice
     * @return bool
     * If the wallet has enought money to settle the entire invoice, settle it.
     * Otherwise if the wallet contains les that the amount only allocate a partial amount to the invoice.
     */
    public function sub($amount, Invoice $invoice)
    {
        if ($this->amount > 0){
            if ($amount > 0 && $amount <= $this->amount){
                $subAmount = $this->amount - $amount;
                $this->transactions()->create([
                    'type' => 'debit',
                    'category' => $invoice->type,
                    'method' => 'wallet',
                    'amount' => $amount,
                    'invoice_reference' => '#'.$invoice->reference,
                    'invoice_order_refference' => '-'
                ]);

                $this->update(['amount' => $subAmount]);
                $invoice->settle();
                $this->allocatePayment($invoice, $amount, "Wallet Payment");

                // Full payment was made from wallet.
                return 'full_payment';

            }elseif($this->amount - $amount < 0){
                $this->transactions()->create([
                    'type' => 'debit',
                    'category' => $invoice->type,
                    'method' => 'wallet',
                    'amount' => $this->amount,
                    'invoice_reference' => '#'.$invoice->reference,
                    'invoice_order_refference' => '-'
                ]);

                $balance = $invoice->balanace - $this->amount;
                $invoice->update([
                    'balance' => $balance
                ]);

                $this->allocatePayment($invoice, $this->amount, "Wallet Payment");
                $this->update(['amount' => 0]);

                // Partial of the invoice has been paid.
                return 'partial_payment';
            }else{
                // Invoice has already been settled.
                return 'settled';
            }
        }
        // Not Enought Money in wallet.
        return 'declined';
    }

    public function withdrawal($amount)
    {
        $user = User::find($this->user_id);

        if ($this->amount - $amount < 0){
            return 'error';
        }else{
            try{
                $available = $this->amount - $amount;
                $this->transactions()->create([
                    'type' => 'debit',
                    'category' => 'deduction',
                    'method' => 'Wallet',
                    'amount' => $amount,
                    'invoice_reference' => '-',
                    'invoice_order_refference' => '-'
                ]);

                $this->update(['amount' => $available]);

                try{
                    $this->sendEmail($user, 'emails.wallet.refund', 'Your refund has been processed');
                }catch (Exception $exception){
                    return;
                }
                return 'success';
            }catch (Exception $exception){
                return 'failed';
            }
        }
    }

    public function allocatePayment($invoice, $amount, $description)
    {
        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Payment',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $amount,
            'ref' => $invoice->reference,
            'method' => 'Wallet',
            'description' => $description,
            'tags' => "Payment",
            'date' => Carbon::now()
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * @param $user
     * @param $view
     * @param $subject
     */
    public function sendEmail($user, $view, $subject)
    {
        if(sendMailOrNot($user, $view)) {
        Mail::send($view, ['user' => $user], function ($m) use ($user, $subject) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email, $user->first_name)->subject($subject);
        });
        }
    }

}

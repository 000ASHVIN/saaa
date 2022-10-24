<?php

namespace App\Store;

use App\Billing\Invoice;
use App\InvoiceOrder;
use App\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $table = 'store_orders';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function getAllGrouped($user = null)
    {
        if (!$user)
            $user = auth()->user();
        $ordersToFilter = $user->orders()->with(['product', 'invoice', 'product.files', 'product.links', 'shippingInformation', 'shippingInformation.status'])->get();
        $orders = $ordersToFilter->filter(function ($order){
            if ($order->invoiceOrder || $order->invoice){
                return $order;
            }
        });
        $groupedOrders = collect([]);
        foreach ($orders as $order) {
            if($order->product) {

                $productId = $order->product->id;
                if ($order->shippingInformation)
                    $shippingStatusId = $order->shippingInformation->status->id;
                else
                    $shippingStatusId = 0;
                if ($order->invoiceOrder){
                    $paymentStatus = $order->invoiceOrder->status;
                }elseif($order->invoice){
                    $paymentStatus = $order->invoice->status;
                }
                $groupedId = $productId . ':' . $shippingStatusId . ':' . $paymentStatus;

                //Group by product and shipping status and payment status
                if (!$groupedOrders->has($groupedId)) {
                    $groupedOrders->put($groupedId, $order);
                } else {
                    $existingOrder = $groupedOrders->get($groupedId);
                    $existingOrder->quantity += $order->quantity;
                    $groupedOrders->put($groupedId, $existingOrder);
                }

            }
        }
        return $groupedOrders->sortBy('shippingInformation.status.id');
    }

    public static function hasPendingOrder($user = null)
    {
        if (!$user)
            $user = auth()->user();

        return $user->orders()->pending()->count() > 0;
    }

    public function scopeNotPending($query)
    {
        return $query->where('is_pending', false);
    }

    public function scopePending($query)
    {
        return $query->where('is_pending', true);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function setProduct($product)
    {
        return $this->product()->associate($product);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setUser($user)
    {
        return $this->user()->associate($user);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceOrder()
    {
        return $this->belongsTo(InvoiceOrder::class, 'invoice_order_id');
    }

    public function setInvoiceOrder($order)
    {
        return $this->invoiceOrder()->associate($order);
    }

    public function setInvoice($invoice)
    {
        return $this->invoice()->associate($invoice);
    }

    public function shippingInformation()
    {
        return $this->hasOne(ShippingInformation::class);
    }

    public function setShippingInformation($shippingInformation)
    {
        return $this->shippingInformation()->save($shippingInformation);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function setListing($listing)
    {
        return $this->listing()->associate($listing);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function setShippingMethod($shippingMethod)
    {
        return $this->shippingMethod()->associate($shippingMethod);
    }

    public function release()
    {
        $this->is_pending = false;
        $this->save();
    }
}

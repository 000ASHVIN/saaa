<?php

namespace App\Http\Controllers\Admin;

use App\Store\Order;
use App\Store\ShippingInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with(['shippingMethod', 'invoice', 'product', 'shippingInformation', 'shippingInformation.address', 'shippingInformation.address.user', 'shippingInformation.status'])->get();
        $orders->transform(function ($order, $orderKey) {
            if ($order->product){
                $order->product->detailedTitle = $order->product->getDetailedTitleAttribute();
                return $order;
            }

        });
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateShippingInformation(Requests\UpdateShippingInformationRequest $request, $shippingInformationId)
    {
        $shipping = ShippingInformation::findOrFail($shippingInformationId);
        $shipping->tracking_code = $request->get('tracking_code', '');
        $shipping->status_id = $request->get('status_id');
        $shipping->save();

        return "Success";
    }
}

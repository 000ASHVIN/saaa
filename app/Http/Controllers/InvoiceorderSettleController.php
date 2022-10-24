<?php

namespace App\Http\Controllers;

use App\InvoiceOrder;
use App\Services\Orders\PdfGenerateInvoiceOrder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class InvoiceorderSettleController extends Controller
{
    private $generate;

    public function __construct(PdfGenerateInvoiceOrder $generate)
    {
        $this->generate = $generate;
    }

    public function show($id)
    {
        $order = InvoiceOrder::find($id);
        if ($order->user->id != auth()->user()->id && !auth()->user()->is('super|accounts|accounts-administrator')) {
            return redirect()->route('dashboard');
        }

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('orders.view', compact('order'));
        return $pdf->inline();
    }

    public function getSettle($id)
    {
        if(request()->has('threeDs'))
            $this->handleThreeDs(request());

        $order = InvoiceOrder::find($id);
        if ($order->balance <= 0) {
            alert()->error('You cannot settle an invoice with no outstanding balance', 'Error');
            return redirect()->route('dashboard');
        }
        if ($order->user->id != auth()->user()->id && !auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }

        $paymentsTotal = $order->total - $order->balance ;
        return view('orders.settle', compact('order', 'paymentsTotal'));
    }
}

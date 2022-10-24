<?php

namespace App\Http\Controllers;

use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Repositories\CreditMemo\PdfGenerateCreditMemo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class CreditMemoController extends Controller
{
    private $memoRepository;
    public function __construct(CreditMemoRepository $memoRepository)
    {
        $this->memoRepository = $memoRepository;
    }

    public function show($invoice, $credit_memo)
    {
        $credit = $this->memoRepository->show($credit_memo);
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('creditNotes.view', compact('credit'));

        return $pdf->inline();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\AppEvents\PromoCode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminCoursesDiscountCodeController extends Controller
{

    public function store(Request $request, $event)
    {
        $validator = \Validator::make($request->except('_token'), [
            'code' => 'required|unique:promo_codes'
        ]);

        if ($validator->fails()){
            alert()->error('Discount code already exists', 'Error');
            return back()->withInput(['tab' => 'discounts']);
        }

        $event = Course::find($event);
        $discount = new PromoCode($request->except('_token'));
        $event->promoCodes()->save($discount);

        alert()->success('Your discount code has been created!', 'Success!');
        return back()->withInput(['tab' => 'discount']);
   }

    public function update(Request $request, $code)
    {
        $code = PromoCode::findByCode($code);
        $code->update($request->except('_token'));

        alert()->success('Your discount code has been updated!', 'Success!');
        return back()->withInput(['tab' => 'discount']);
   }

    public function destroy($code)
    {
        $code = PromoCode::findByCode($code);
        $code->delete();

        alert()->success('Your discount code has been deleted!', 'Success!');
        return back()->withInput(['tab' => 'discount']);
   }
}

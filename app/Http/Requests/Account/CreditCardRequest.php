<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\Request;

class CreditCardRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number' => 'required|numeric',
            'holder' => 'required',
            'exp_month' => 'required|numeric',
            'exp_year' => 'required|numeric',
            'cvv' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'holder.required' => 'Card holder name is required',
            'number.required' => 'Credit card number is required',
            'exp_month.required' => 'Credit card expiry month is required',
            'exp_year.required' => 'Credit card expiry year is required',
            'cvv.required' => 'Credit card CVV or security code is required'
        ];
    }
}

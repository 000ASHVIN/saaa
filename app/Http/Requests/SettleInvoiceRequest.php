<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SettleInvoiceRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'terms' => ['required', 'accepted'],
            'paymentOption' => ['required'],            
        ];
    }

    public function messages()
    {
        return [
            'terms.required' => 'You must accept the Terms & Conditions to proceed.',
            'terms.accepted' => 'You must accept the Terms & Conditions to proceed.',
            'paymentOption.required' => 'Please select a payment option.',            
        ];
    }
}

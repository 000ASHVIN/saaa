<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CompanyInformationRequest extends Request
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
            'title' => 'required',
            'company_vat' => 'required',
            'plan_id' => 'required',
            'user_id' => 'required',
            'employees' => 'required',
            'address_line_one' => 'required',
            'address_line_two' => 'required',
            'province' => 'required',
            'country' => 'required',
            'city' => 'required',
            'area_code' => 'required',
        ];
    }
}

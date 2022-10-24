<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CompanyProfessionRegistrationRequest extends Request
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
            'company' => 'required',
            'city' => 'required',
            'country' => 'required',
            'province' => 'required',
            'area_code' => 'required',
            'company_vat' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'cell' => 'required|numeric',
            'selected_plan' => 'required',
            'address_line_one' => 'required',
            'address_line_two' => 'required',
            'alternative_cell' => 'required',
            'employees' => 'required|numeric',
            'email' => 'required|email|unique:company_registration_professions',
            'id_number' => 'required|numeric|unique:company_registration_professions',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddCpdsRequest extends Request
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
            'dateFormatted' => ['required', 'date'],
            'hours' => ['required'],
            'source' => ['required']
        ];
    }
}

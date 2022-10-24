<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyProfessionRegistration extends Model
{
    protected $table = 'company_registration_professions';
    protected $fillable = [
        'city',
        'cell',
        'email',
        'company',
        'country',
        'province',
        'area_code',
        'id_number',
        'employees',
        'last_name',
        'first_name',
        'company_vat',
        'selected_plan',
        'address_line_one',
        'address_line_two',
        'alternative_cell',
    ];
}

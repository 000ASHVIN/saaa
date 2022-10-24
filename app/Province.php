<?php

namespace App;

class Province
{
    const provinces = [
        'Eastern Cape' => 'EC',
        'Free State' => 'FS',
        'Gauteng' => 'GP',
        'KwaZulu-Natal' => 'KZN',
        'Limpopo' => 'LP',
        'Mpumalanga' => 'MP',
        'North West' => 'NW',
        'Northern Cape' => 'NC',
        'Western Cape' => 'WC',
        'Etosha' => 'ETO',
        'Oshana' => 'OSH',
        'Oshakati' => 'OSK',
        'Windhoek' => 'WDH'
    ];
    const provincesByCode = [
        'EC' => 'Eastern Cape',
        'FS' => 'Free State',
        'GP' => 'Gauteng',
        'KZN' => 'KwaZulu-Natal',
        'LP' => 'Limpopo',
        'MP' => 'Mpumalanga',
        'NW' => 'North West',
        'NC' => 'Northern Cape',
        'WC' => 'Western Cape',
        'ETO' => 'Etosha',
        'OSH' => 'Oshana',
        'OSK' => 'Oshakati',
        'WDH' => 'Windhoek',
        'others'=>'others'
    ];

    public static function byCode($code)
    {
        return static::provincesByCode[$code];
    }
}
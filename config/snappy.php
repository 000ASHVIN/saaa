<?php

return array(
    'pdf' => array(
        'enabled' => true,
        'binary' => base_path('vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf'),
        'timeout' => false,
        'disable-smart-shrinking' => true,
        'options' => [
            'dpi' => 300,
            'page-size' => 'A4',
            'margin-top' =>  '5mm',
            'margin-bottom' => '5mm',
            'margin-left' => '5mm',
            'margin-right' => '5mm',
            'zoom' => '1.25'
        ],
    ),
    'image' => array(
        'enabled' => true,
        'binary' => base_path('vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf'),
        'timeout' => false,
        'disable-smart-shrinking' => true,
        'options' => [
            'dpi' => 300,
            'page-size' => 'A4',
            'margin-top' =>  '5mm',
            'margin-bottom' => '5mm',
            'margin-left' => '5mm',
            'margin-right' => '5mm',
            'zoom' => '1.25'
        ],
    ),
);
<?php
return [
    'font_path' =>base_path('public/sources/__fonts/Farsi_numerals/ttf/'),
    'font_data' => [
        'iransans' => [
            'R'  => 'IRANSansWeb(FaNum)_Light.ttf',    // regular font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ],
        'iransans_ul' => [
            'R'  => 'IRANSansWeb(FaNum)_UltraLight.ttf',    // regular font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ] ,
        'iransans_med' => [
            'R'  => 'IRANSansWeb(FaNum)_Medium.ttf',    // regular font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ] ,
    ],
    'mode'                  => 'utf-8',
    'format'                => 'A4',
    'author'                => '',
    'subject'               => '',
    'keywords'              => '',
    'creator'               => 'Laravel Pdf',
    'display_mode'          => 'fullpage',
    'tempDir'               => public_path('./pdf_temp')
];

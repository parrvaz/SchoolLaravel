<?php

return [
    'paginate'=>5,
    'bigPaginate'=>20,
    "day"=>[
        1 => 'sat', // شنبه
        2 => 'sun', // یکشنبه
        3 => 'mon', // دوشنبه
        4 => 'tue', // سه‌شنبه
        5 => 'wed', // چهارشنبه
        6 => 'thu', // پنج‌شنبه
        7 => 'fri'  // جمعه (اختیاری)
    ],
    "roles"=>[
        "admin"=>1,
        "manager"=>2,
        "teacher"=>3,
        "student"=>4,
        "parent"=>5,
        "assistant"=>6,
    ],
    "fields"=>[
        1=>"ریاضی و فیزیک",
        2=>"تجربی",
        3=>"انسانی",
    ],
    "absents"=>[
        1=>"😓",
        2=>"😢",
        5=>"😳",
        8=>"🤯",
        9=>"😡",
    ],
    "files"=>[
      "photos"=>1,
      "pdfs"=>2,
      "voices"=>3,
    ],

    "SMS"=>[
        "token"=>"14EC1755C1D996705342B07BDA108BE66E67F8BF",
//        "url"=>"14EC1755C1D996705342B07BDA108BE66E67F8BF",
    ],

];

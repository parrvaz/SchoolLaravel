<?php


return [

'paths' => ['api/*', 'sanctum/csrf-cookie'], // مسیرهایی که CORS برای آن‌ها اعمال می‌شود

'allowed_methods' => ['*'], // همه متدهای HTTP مجاز هستند

'allowed_origins' => ['*'], // همه دامنه‌ها مجاز هستند (برای امنیت بیشتر می‌توانید دامنه خاصی را مشخص کنید)

'allowed_origins_patterns' => [],

'allowed_headers' => ['*'], // همه هدرها مجاز هستند

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => false, // اگر باید کوکی‌ها و اعتبارات ارسال شوند، این را true کنید
];

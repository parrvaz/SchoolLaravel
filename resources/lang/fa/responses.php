<?php
/**
 * Created by PhpStorm.
 * User: Reza
 * Date: 09/03/2020
 * Time: 08:50 PM
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'response' => [
        'success' => 'عملیات با موفقیت انجام شد',
        'error' => 'داده ها اشتباه هستند'
    ],
    'ticket' => [
        'save' => 'تیکت با موفقیت ثبت شد',
        'delete' => 'تیکت با موفقیت حذف شد'
    ],
    'register' => [
        'verification' => [
            'send' => 'کد فعال سازی با موفقیت ارسال شد',
            'invalid' => 'کد وارد شده معتبر نمی باشد'
        ]
    ],
    'throttle' => 'تعداد دفعات ورود رمز عبور بیش از حد مجاز است لطفا پس از :seconds ثانیه تلاش کنید ',
    'null'=>'',
    'success'=>'عملیات با موفقیت انجام شد',
    'avg'=> 'تمام کالاها با نرخ میانگین به سال مالی بعد منتقل می‌شوند. در صورت تمایل می‌توانید در تراز افتتاحیه آن‌ها را تغییر دهید.',

    'error'=>[
        "dontExist"=> "آیتم وجود ندارد",
        "storeBefore"=> "زنگ های درسی قبلا تعریف شده اند",
        "haveNotPlan"=> "مشاور هیچ برنامه مطالعاتی برای شما ثبت نکرده است",
        "fatherPhone"=> "تلفن دانش آموز و پدر نباید یکسان باشند",
        "hasSchedule"=> "به علت استفاده از این زنگ در برنامه کلاسی امکان حذف آن وجود ندارد",
        "hasAbsent"=> "به علت استفاده از این آیتم در حضور و غیاب امکان حذف آن وجود ندارد",
        "reference"=>'شماره ارجاع اشتباه است',
        "payPrice"=>'مبلغ وارد شده بیش از مبلغ فاکتور است',
        'sum'=>'مجموع صحیح نمی‌ باشد',
        'null'=>'آیتم وجود ندارد',
        'manual'=>'این  سند دستی نیست.',
        'draft'=>'فاکتور هنوز تایید نشده است',
        'type'=>'درخواست اشتباه است',
        'permission'=>'شما اجازه دسترسی ندارید',
        'formatFile'=>[
            'xlsx'=>'فرمت فایل آپلود شده باید به صورت xlsx باشد.',
            'zip'=>'فرمت فایل آپلود شده باید به صورت zip باشد.',
        ],
        'register'=>'کاربری با این شماره موبایل یافت نشد.کاربری که قصد دسترسی دادن به ایشان را دارید باید در فینتو ثبت نام کرده باشند',
        'permissionForUser'=>'دسترسی برای این کاربر فعال نمی‌ باشد',
        'hasReturnFactor'=>'به علت برگشت خوردن این فاکتور امکان حذف وجود ندارد.',
        'workshops'=>'ابتدا در سیستم حقوق و دستمزد یک کارگاه تعریف کنید',
        'salaryExist'=>'حقوقی برای تاریخ انتخاب شده ثبت نشده است.',
        'both'=>'فقط یکی از دو فیلد بدهکار و بستانکار باید مقداری بیش از صفر داشته باشند.',
        'hasWarehouseFactorDelete'=>'به علت ثبت حواله انبار برای این فاکتور، امکان حذف آن وجود ندارد',
        'hasWarehouseFactorUpdate'=>'به علت ثبت حواله انبار برای این فاکتور، امکان ویرایش آن وجود ندارد',
        'factor'=>'به علت ثبت تراکنش، امکان تغییر ضریب تبدیل وجود ندارد',
        'subUnit'=>'به علت ثبت تراکنش، امکان تغییر واحد فرعی وجود ندارد',
        'delete'=>[
            'soled'=> 'به علت فروخته شدن کالای موجود در این فاکتور، امکان حذف فاکتور وجود ندارد.',

            'category'=>[
                'haveChild'=>' تا زمانی که شخصی با این دسته بندی(یا زیر شاخه های این دسته بندی) موجود است،امکان حذف آن را ندارید',
                'specific'=>'این دسته بندی قابل حذف نیست.',
            ],
            'transaction'=>[
                'have'=>'به علت ثبت تراکنش امکان حذف وجود ندارد',
                'check'=>'به علت عملیات روی یکی از چک ها امکان حذف وجود ندارد',
            ],
            'shareholder'=>[
                'have'=>'به علت ثبت به عنوان سهامدار، امکان حذف وجود ندارد',
            ],
            'receipt'=>[
                'have'=>'به علت ثبت حواله امکان حذف وجود ندارد',
            ],
            'seller'=>[
                'have'=>'به علت ثبت به عنوان فروشنده، امکان حذف وجود ندارد',
            ],
        ],
        'check'=>[
            'spend'=>'امکان بازگشت به حالت عادی برای چک خرج شده وجود ندارد',
            'pass'=>' چک قبلا پاس شده است',
            'type'=>'درخواست اشتباه است',
            'ol'=>'چک در تراز افتتاحیه ثبت شده است',
        ],
        'transaction'=>[
            'price'=>'مبلغ پرداختی بیش از وجه فاکتور است',
            'payed'=>'به علت ثبت تراکنش امکان تغییر وضعیت وجود ندارد',
        ],
        'purchase'=>[
            'person'=>'برای فاکتور پرداخت با نام شخص دیگری ثبت شده است',
            'retNum'=>'تعداد وارد شده برای کالای :name کمتر از مقدار برگشتی آن است.',
            'retRef'=>'شماره ارجاع اشتباه است',
        ],
        'wastage'=>[
            'hasWastage'=>'به علت ثبت کالای :name موجود در این فاکتور به عنوان ضایعات، امکان ویرایش وجود ندارد',
        ],
        'account'=>[
            'parent_price'=>'در برخی اسناد حسابداری، حساب کل (حسابی که دارای زیر مجموعه می‌ باشد) به عنوان حساب مرجع انتخاب شده است. برای بستن سال مالی نیاز است که آن ها را به حساب های زیرمجموعه شان تغییر دهید.',
            'parent_hasAccItem'=>'حساب مرجع نباید دارای سند حسابداری باشد.',
            'notLeaf'=>'حساب انتخاب شده مجاز نمی باشد',
        ],
        'inventory'=>[
            'mines'=>'تعداد برخی کالاها در این دوره زمانی کمتر از صفر است. لطفا با ثبت خرید، تعداد موجودی این کالا را افزایش دهید یا بازه زمانی دیگری را انتخاب نمایید',
            'purMines'=>'تعداد برخی کالاها کمتر از صفر است. لطفا با ثبت خرید، موجودی این کالاها را افزایش دهید.',
        ],
        'emptyShareholder'=>'هیچ سهامداری در سیستم ثبت نشده است. برای ویرایش تراز افتتاحیه ابتدا سهامداران را ثبت کنید. ',
        'emptyShareholderClose'=>'هیچ سهامداری در سیستم ثبت نشده است. برای بستن سال مالی ابتدا سهامداران را ثبت کنید. ',
        'barcodeUnique'=>'بارکد قبلا انتخاب شده است.',
        'hasDefault'=>'یک آیتم را به عنوان قیمت پیش فرض انتخاب کنید',
        'doublePriceService'=>'قیمت نمی تواند اعشاری باشد',
        'doublePriceFactor'=>'حاصل ضرب قیمت در ضریب تبدیل، می بایست عددی صحیح باشد',

        'warehouseInConsistent'=> 'موجودی انبار و موجودی سیستمی کالاها برابر نیستند.',
        'warehouseType'=> 'آیتم انتخاب شده وجود ندارد',
        'warehouseDraft'=> 'امکان ثبت حواله برای فاکتورهای پیش نویس وجود ندارد',
        'warehouseNum'=> 'تعداد انتخاب شده بیش از حد مجاز است',
        'num'=> 'تعداد انتخاب شده برای کالای :name بیش از حد مجاز است',
        'unitPrice'=> "قیمت واحد در ردیف :row باید عدد صحیح باشد",
        'locked'=> "مقدار فیلد قفل  برای آیتم ردیف :row اشتباه است",
        'allLocked'=> "ارسال تمام آیتم هایی که قفل هستند الزامی می باشد",
    ]
];

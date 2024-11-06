<?php

namespace App\Schedules;

use App\Jobs\AddStudyPlansJob;
use Illuminate\Console\Scheduling\Schedule;

class AddStudyPlansSchedule
{
    public function __invoke(Schedule $schedule)
    {
        // تنظیم Job برای اجرا در هر شب
        $schedule->job(new AddStudyPlansJob)->dailyAt('00:00');
    }
}

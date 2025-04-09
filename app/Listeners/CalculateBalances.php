<?php

namespace App\Listeners;

use App\Events\ExamFinaled;
use App\Http\Controllers\CalculateIndicatorsController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalculateBalances
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ExamFinaled $event): void
    {
        (new CalculateIndicatorsController())->calculateExam($event->exam);
    }
}

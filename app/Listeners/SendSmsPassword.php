<?php

namespace App\Listeners;

use App\Events\UserCreate;
use App\Http\Controllers\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsPassword implements ShouldQueue
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
    public function handle(UserCreate $event): void
    {
        (new SMSController())->UserInformationSms( $event->user);
        (new SMSController())->UserAddInList( $event->user);
    }


}


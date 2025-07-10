<?php

namespace App\Listeners;

use App\Events\RoleDelete;
use App\Http\Controllers\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsAdd implements ShouldQueue
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
    public function handle(RoleDelete $event): void
    {
        (new SMSController())->UserAddInList( $event->user);
    }
}

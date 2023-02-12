<?php

namespace App\Listeners;

use App\Events\NewOrderProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewOrderProcessed  $event
     * @return void
     */
    public function handle(NewOrderProcessed $event)
    {
        $products = $event->products;
        
        // check if should send email

                
    }
}

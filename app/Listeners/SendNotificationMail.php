<?php

namespace App\Listeners;

use App\Events\NewOrderProcessed;
use App\Http\Services\IngredientService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationMail implements ShouldQueue
{
    use InteractsWithQueue;
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
        $ingredientsId = (array) $event->ingredientsId;

        $iService = new IngredientService;
        $iService->processMailNotification($ingredientsId);
    }
}

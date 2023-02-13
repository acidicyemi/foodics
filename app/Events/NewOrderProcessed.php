<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $products;
    public $ingredientsId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $products, $ingredientsId)
    {
        $this->products = $products;
        $this->ingredientsId = array_unique($ingredientsId);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

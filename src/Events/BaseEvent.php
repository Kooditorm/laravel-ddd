<?php

namespace DDDCore\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @class BaseEvent
 * @package DDDCore\Events
 */
class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn():Channel
    {
        return new PrivateChannel('channel-name');
    }
}

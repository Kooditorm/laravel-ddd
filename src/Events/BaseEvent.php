<?php

namespace DDDCore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

abstract class BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
}

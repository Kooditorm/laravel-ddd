<?php

namespace DDDCore\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AsyncEventListener extends EventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public string $queue = 'event';

}

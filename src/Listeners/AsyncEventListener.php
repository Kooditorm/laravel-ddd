<?php

namespace DDDCore\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * 监听异步event事件
 *
 * @class AsyncEventListener
 * @package DDDCore\Listeners
 */
class AsyncEventListener extends EventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public string $queue = 'event';

}

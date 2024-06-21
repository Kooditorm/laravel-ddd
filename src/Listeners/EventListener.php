<?php

namespace DDDCore\Listeners;

use DDDCore\Events\Event;

/**
 * @class EventListener
 * @package DDDCore\Listeners
 */
abstract class EventListener
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
     * @param  Event  $event
     * @return void
     */
    abstract public function handle(Event $event): void;
}

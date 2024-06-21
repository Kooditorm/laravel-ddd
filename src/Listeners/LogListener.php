<?php

namespace DDDCore\Listeners;

use DDDCore\Events\Event;
use Illuminate\Support\Facades\Log;

class LogListener extends SyncEventListener
{
    /**
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event):void
    {
        $data = $event->data;
        [$begin, $finish, $textContext] = $data;
        $c = $begin->format('c');
        [$datetime] = explode('+', $c);
        $date    = $datetime.'.'.$begin->format('u');
        $diff    = $finish->diff($begin);
        $i       = $diff->d;
        $s       = $diff->s;
        $f       = $diff->f;
        $useTime = $i * 60 + $s + $f;

        $textContext['date']    = $date;
        $textContext['useTime'] = $useTime;

        Log::info('', $textContext);
    }
}

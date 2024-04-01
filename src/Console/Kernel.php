<?php

namespace DDDCore\Console;

use DddCore\Libraries\Laravel\Contracts\Console\LaravelKernel;
use Illuminate\Foundation\Console\Kernel as LFCKernel;

class Kernel extends LFCKernel implements LaravelKernel
{

    public function commands(): void
    {
        echo 'commands';
    }
}

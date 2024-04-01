<?php

namespace DDDCore\Console;

use  Illuminate\Foundation\Console\Kernel as LaravelKernel;
use  DddCore\Libraries\Laravel\Contracts\Console\Kernel as LaravelKernelContract;

class Kernel extends LaravelKernel implements LaravelKernelContract
{

    public function commands()
    {
        echo 'commands';
    }
}

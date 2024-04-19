<?php

namespace DDDCore\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\AggregateServiceProvider;

class DDDCoreServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{

    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected $providers = [
        ListenDBServiceProvider::class,
        ArtisanServiceProvider::class
    ];
}

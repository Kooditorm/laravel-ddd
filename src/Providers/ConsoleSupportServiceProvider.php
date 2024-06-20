<?php

namespace DDDCore\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\AggregateServiceProvider;

/**
 * @class ConsoleSupportServiceProvider
 * @package DDDCore\Providers
 */
class ConsoleSupportServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        MarkerServiceProvider::class
    ];
}

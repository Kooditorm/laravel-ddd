<?php

namespace DDDCore\Console\Makers;

/**
 * @class ExceptionCommand
 * @package DDDCore\Console\Makers
 */
class ExceptionCommand extends MakerCommand
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'gen:Exception';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new Exception.';

    /**
     * @inheritDoc
     *
     * @var bool
     */
    protected bool $single = true;

    /**
     * Execute the command.
     *
     * @return void
     * @see fire()
     */
    public function handle(): void
    {
        $this->laravel->call([$this, 'fire'], func_get_args());
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire(): void
    {

    }
}

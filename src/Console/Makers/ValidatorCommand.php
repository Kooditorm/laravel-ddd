<?php

namespace DDDCore\Console\Makers;

/**
 * @class ValidatorCommand
 * @package DDDCore\Console\Makers
 */
class ValidatorCommand extends MakerCommand
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'gen:validator';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new Validator.';

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

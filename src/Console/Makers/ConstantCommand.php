<?php

namespace DDDCore\Console\Makers;

/**
 * @class ConstantCommand
 * @package DDDCore\Console\Makers
 */
class ConstantCommand extends MakerCommand
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'gen:Constant';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new Constant.';

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
    public function fire():void
    {

    }
}

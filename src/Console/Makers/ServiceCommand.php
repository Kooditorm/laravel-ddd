<?php

namespace DDDCore\Console\Makers;

class ServiceCommand extends MakerCommand
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'gen:service';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new Service.';

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

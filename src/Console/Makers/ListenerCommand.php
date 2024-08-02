<?php

namespace DDDCore\Console\Makers;

use DDDCore\Console\Makers\Generator\ListenerAsyncGenerator;
use DDDCore\Console\Makers\Generator\ListenerSyncGenerator;
use Exception;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @class ListenerCommand
 * @package DDDCore\Console\Makers
 */
class ListenerCommand extends MakerCommand
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'gen:Listener';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new Listener.';

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
        $generators = new Collection();
        try {
            $generators->push(new ListenerAsyncGenerator([
                'name'   => $this->argument('name'),
                'action' => $this->argument('action'),
                'residue' => $this->argument('residue')
            ]));

            $generators->push(new ListenerSyncGenerator([
                'name'   => $this->argument('name'),
                'action' => $this->argument('action'),
                'residue' => $this->argument('residue'),
                'lastAction' => true,
            ]));

            $generators->each(function ($generator){
                $generator->run();
            });
            $this->tips();
        } catch (Exception $e) {
            $this->tips($e);
        }
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of class being generated.',
                null
            ],
            [
                'action',
                InputArgument::REQUIRED,
                'Operating on this type of action.',
                null
            ],
            [
                'residue',
                InputArgument::REQUIRED,
                'Operating on this type of residue.',
                null
            ]
        ];
    }
}

<?php

namespace DDDCore\Console\Makers;


use DDDCore\Console\Makers\Generator\ServiceGenerator;
use Exception;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @class ServiceCommand
 * @package DDDCore\Console\Makers
 */
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
        try {
            (new ServiceGenerator([
                'name' => $this->argument('name'),
                'action' => $this->argument('action')
            ]))->run();
            $this->tips();
        }catch (Exception $e) {
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
        ];
    }
}

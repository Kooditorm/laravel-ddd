<?php

namespace DDDCore\Console\Makers;

use DDDCore\Console\Makers\Generator\ValidatorGenerator;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
        try {
            (new ValidatorGenerator([
                'name'   => $this->argument('name'),
                'action' => $this->argument('action'),
                'rules'  => $this->option('rules'),
                'force'  => $this->option('force'),
                'lastAction' => true,
            ]))->run();
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
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return [
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ],
        ];
    }
}

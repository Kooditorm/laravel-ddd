<?php

namespace DDDCore\Console\Makers;

use DDDCore\Console\Makers\Generator\DTOGenerator;
use Exception;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @class DTOCommand
 * @package DDDCore\Console\Makers
 */
class DTOCommand extends MakerCommand
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'gen:DTO';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new DTO.';

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
            (new DTOGenerator([
                'name' => $this->argument('name'),
                'action' => $this->argument('action'),
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
}

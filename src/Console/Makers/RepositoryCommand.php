<?php

namespace DDDCore\Console\Makers;

use Exception;
use Illuminate\Support\Collection;
use DDDCore\Console\Makers\Generator\ModelGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @class RepositoryCommand
 * @package DDDCore\Console\Makers
 */
class RepositoryCommand extends MakerCommand
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'gen:repository';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new repository.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type = 'Repository';

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
        $generators = new Collection();
        try{
            $modelGenerator = new ModelGenerator([
                'name'     => $this->argument('name'),
                'action'   => $this->argument('action'),
                'fillable' => $this->option('fillable'),
                'force'    => $this->option('force')
            ]);

            if (!$this->option('skip-model')) {
                $generators->push($modelGenerator);
            }
        }catch (Exception $e){
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
                'fillable',
                null,
                InputOption::VALUE_OPTIONAL,
                'The fillable attributes.',
                null
            ],
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null
            ],
            [
                'validator',
                null,
                InputOption::VALUE_OPTIONAL,
                'Adds validator reference to the repository.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ],
            [
                'skip-migration',
                null,
                InputOption::VALUE_NONE,
                'Skip the creation of a migration file.',
                null,
            ],
            [
                'skip-model',
                null,
                InputOption::VALUE_NONE,
                'Skip the creation of a model.',
                null,
            ],
        ];
    }
}

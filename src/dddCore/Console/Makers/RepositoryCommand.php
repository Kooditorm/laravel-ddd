<?php
/**
 * Author: oswin
 * Time: 2021/11/13-15:02
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;

use App\Infrastructure\Libraries\Laravel\Console\command;
use Exception;
use Illuminate\Support\Collection;
use App\Infrastructure\Libraries\Prettus\RepositoryInterfaceGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RepositoryCommand extends Command
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
    public function fire(): void
    {
        $generators = new Collection();
        try {
            $modelGenerator = new ModelGenerator([
                'name'     => $this->argument('name'),
                'action'   => $this->argument('action'),
                'fillable' => $this->option('fillable'),
                'force'    => $this->option('force')
            ]);

            if (!$this->option('skip-model')) {
                $generators->push($modelGenerator);
            }

            $generators->push(new RepositoryInterfaceGenerator([
                'name'   => $this->argument('name'),
                'action' => $this->argument('action'),
                'force'  => $this->option('force'),
                'lastAction' => true,
            ]));

            $model = $modelGenerator->getRootNamespace().'\\'.$modelGenerator->getName();
            $model = str_replace([
                "\\",
                '/'
            ], '\\', $model);
            (new RepositoryEloquentGenerator([
                'name'      => $this->argument('name'),
                'action'    => $this->argument('action'),
                'rules'     => $this->option('rules'),
                'validator' => $this->option('validator'),
                'force'     => $this->option('force'),
                'model'     => $model
            ]))->run();

            foreach ($generators as $generator) {
                $generator->run();
            }
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

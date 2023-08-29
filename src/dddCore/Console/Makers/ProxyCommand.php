<?php
/**
 * Author: oswin
 * Time: 2022/5/27-15:52
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;

use App\Infrastructure\Libraries\Laravel\Console\command;
use Exception;
use Symfony\Component\Console\Input\InputArgument;


class ProxyCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'gen:Proxy';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new Proxy.';

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
        try {
            (new ProxyGenerator([
                'name'  => $this->argument('name'),
                'action' => $this->argument('action'),
                'residue' => $this->argument('residue'),
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
            [
                'residue',
                InputArgument::REQUIRED,
                'Operating on this type of residue.',
                null
            ]
        ];
    }
}

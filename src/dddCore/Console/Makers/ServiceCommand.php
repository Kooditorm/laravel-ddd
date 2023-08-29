<?php
/**
 * Author: oswin
 * Time: 2021/12/28-17:40
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;

use Exception;
use App\Infrastructure\Libraries\Laravel\Console\command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;

class ServiceCommand extends Command
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
        $generators = new Collection();

        try {

            $generators->push(new ServiceImplGenerator([
                'name' => $this->argument('name'),
                'action' => $this->argument('action')
            ]));

            $generators->push(new ServiceGenerator([
                'name' => $this->argument('name'),
                'action' => $this->argument('action'),
                'lastAction' => true,
            ]));

            foreach ($generators as $generator) {
                $generator->run();
            }
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

<?php

namespace DDDCore\Console\Commands;

class InitCommand extends BaseCommand
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'init';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Domain driven system initialization command';


    public function __construct(?string $name = null)
    {

        if (!empty($name)) {
            $this->signature = str_replace('init', 'ddd:'.$name, $this->signature);
        }
        parent::__construct();
    }

    public function handle(): void
    {
        echo '初始化脚本';
    }
}

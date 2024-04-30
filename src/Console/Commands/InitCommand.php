<?php

namespace DDDCore\Console\Commands;

class InitCommand extends BaseCommand
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'ddd:init';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Domain driven system initialization command';

    public function handle(): void
    {
        echo '初始化脚本';
    }
}

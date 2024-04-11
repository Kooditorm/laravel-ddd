<?php

namespace DDDCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $t1     = microtime(true);
        $result = parent::execute($input, $output);
        $t2     = microtime(true);
        Log::info('The execution time of command '.$this->name.' is: '.round(($t2 - $t1), 4).'s');
        echo round(($t2 - $t1), 4).'s';
        return $result;
    }
}

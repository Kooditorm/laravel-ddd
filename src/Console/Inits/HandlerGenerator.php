<?php

namespace DDDCore\Console\Inits;

use DDDCore\Libraries\Prettus\Generator;

class HandlerGenerator extends Generator
{

    protected $stub = 'Handler';


    public function getPathConfigNode(): string
    {
        return 'Handler';
    }


    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/Handler.php';
    }
}

<?php

namespace DDDCore\Console\Makers\Generator;

/**
 * @class ListenerAsyncGenerator
 * @package DDDCore\Console\Makers\Generator
 */
class ListenerAsyncGenerator extends ListenerGenerator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'listener/async-listener';

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/'.config('repository.generator.inputPath').'AsyncListener.php';
    }
}

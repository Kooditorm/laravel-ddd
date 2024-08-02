<?php

namespace DDDCore\Console\Makers\Generator;

/**
 * @class ListenerSyncGenerator
 * @package DDDCore\Console\Makers\Generator
 */
class ListenerSyncGenerator extends ListenerGenerator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'listener/sync-listener';

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/'.config('repository.generator.inputPath').'SyncListener.php';
    }
}

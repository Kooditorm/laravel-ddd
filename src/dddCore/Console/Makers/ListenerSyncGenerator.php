<?php
/**
 * Author: oswin
 * Time: 2022/5/27-15:59
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;



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

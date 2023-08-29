<?php
/**
 * Author: oswin
 * Time: 2022/5/27-15:59
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;

class ConstantCacheGenerator extends ConstantGenerator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'constant/cache-constant';

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/'.config('repository.generator.inputPath').'CacheConstant.php';
    }
}

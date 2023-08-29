<?php
/**
 * Author: oswin
 * Time: 2021/12/28-18:44
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;


use App\Infrastructure\Libraries\Prettus\Generator;

class ServiceImplGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'service/impl';

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace(): string
    {
        return parent::getRootNamespace().$this->getPathConfigNode()."\Impl";
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'Services';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/Impl/'.$this->getName().'ServiceImpl.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return config('repository.generator.basePath', app()->path());
    }


    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements(): array
    {
        return array_merge(parent::getReplacements(), [
            'path' => parent::getRootNamespace()
        ]);
    }
}

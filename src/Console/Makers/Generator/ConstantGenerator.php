<?php

namespace DDDCore\Console\Makers\Generator;

use DDDCore\Libraries\Prettus\Generator;

class ConstantGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'constant/constant';

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'Constants';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/'.config('repository.generator.inputPath').'Constant.php';
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
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace(): string
    {
        return parent::getRootNamespace().$this->getPathConfigNode();
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements(): array
    {
        return array_merge(parent::getReplacements(), [
            'domain' => config('repository.generator.inputPath')
        ]);
    }

    public function run()
    {
        return $this->onlyRun();
    }

}

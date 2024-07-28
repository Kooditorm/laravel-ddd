<?php

namespace DDDCore\Console\Makers\Generator;

use DDDCore\Libraries\Prettus\Generator;

/**
 * @class RepositoryGenerator
 * @package DDDCore\Console\Makers\Generator
 */
class RepositoryGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'repository/repository';

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace(): string
    {
        return parent::getRootNamespace().$this->getConfigGeneratorClassPath($this->getPathConfigNode());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'repositories';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return  $this->getBasePath().DIRECTORY_SEPARATOR.$this->getConfigGeneratorClassPath($this->getPathConfigNode(), true).DIRECTORY_SEPARATOR.$this->getName().'Repository.php';
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
            'path'     => parent::getRootNamespace()
        ]);
    }
}

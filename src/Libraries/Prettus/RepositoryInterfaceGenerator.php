<?php

namespace DDDCore\Libraries\Prettus;

/**
 * @class RepositoryInterfaceGenerator
 * @package DDDCore\Libraries\Prettus
 */
class RepositoryInterfaceGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'repository/interface';

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace(): string
    {
        return parent::getRootNamespace() .$this->getConfigGeneratorClassPath($this->getPathConfigNode());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'interfaces';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath() . '/' .$this->getConfigGeneratorClassPath($this->getPathConfigNode(), true). '/' . $this->getName() . 'Repository.php';
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
}

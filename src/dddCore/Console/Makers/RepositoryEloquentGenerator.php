<?php
/**
 * Author: oswin
 * Time: 2021/12/25-17:01
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;

use App\Infrastructure\Libraries\Prettus\Generator;
use DDDCore\Console\Makers\Traits\FieldTrait;

class RepositoryEloquentGenerator extends Generator
{
    use FieldTrait;
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'repository/eloquent';

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace(): string
    {
        return parent::getRootNamespace().$this->getConfigGeneratorClassPath($this->getPathConfigNode()).'\Eloquent';
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
        return  $this->getBasePath().'/'.$this->getConfigGeneratorClassPath($this->getPathConfigNode(), true).'/Eloquent/'.$this->getName().'RepositoryEloquent.php';
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

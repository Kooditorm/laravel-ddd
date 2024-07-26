<?php

namespace DDDCore\Console\Makers\Generator;

use DDDCore\Libraries\Prettus\Generator;
use DDDCore\Traits\FieldTrait;
use Illuminate\Support\Str;

/**
 * @class ModelGenerator
 * @package DDDCore\Console\Makers\Generator
 */
class ModelGenerator extends Generator
{
    use FieldTrait;

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'entity/model';

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return '';
    }

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
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getConfigGeneratorClassPath($this->getPathConfigNode(), true).'/'.$this->getName().'.php';
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
            'fillable' => $this->getFilling(),
            'table'    => $this->getTable()
        ]);
    }

    /**
     * Get table.
     *
     * @return string
     */
    public function getTable(): string
    {
        return Str::plural(Str::snake($this->getName()));
    }

}

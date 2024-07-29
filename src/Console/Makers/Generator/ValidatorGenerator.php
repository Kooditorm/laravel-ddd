<?php

namespace DDDCore\Console\Makers\Generator;

use DDDCore\Libraries\Prettus\Generator;
use DDDCore\Traits\FieldTrait;

class ValidatorGenerator extends Generator
{
    use FieldTrait;

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'validator/validator';

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'Validators';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/'.$this->getName().'Validator.php';
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
            'rules' => $this->getFields()
        ]);
    }

    public function replace(): string
    {
        return $this->getFields();
    }
}

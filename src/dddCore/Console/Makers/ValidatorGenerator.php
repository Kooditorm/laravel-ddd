<?php
/**
 * Author: oswin
 * Time: 2022/5/27-15:59
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;

use App\Infrastructure\Console\Makers\Traits\FieldTrait;
use App\Infrastructure\Libraries\Prettus\Generator;

class ValidatorGenerator extends Generator
{
    use FieldTrait;

    /**
     * The placeholder for repository bindings
     *
     * @var string
     */
    public string $bindStartPlaceholder = '//:fields:';

    /**
     * The placeholder for repository bindings
     *
     * @var string
     */
    public string $bindEndPlaceholder = '//:end-fields:';

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
            'rules' => $this->getRules()
        ]);
    }

    public function replace(): string
    {
        return $this->getRules();
    }

}

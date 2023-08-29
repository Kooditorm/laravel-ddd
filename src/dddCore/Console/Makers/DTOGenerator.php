<?php
/**
 * Author: oswin
 * Time: 2021/11/6-18:25
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;

use App\Infrastructure\Libraries\Prettus\Generator;
use App\Infrastructure\Console\Makers\Traits\FieldTrait;


class DTOGenerator extends Generator
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
    protected $stub = 'DTO/DTO';

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'DTO';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/'.$this->getName().'DTO.php';
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
            'filling' => $this->getFilling(),
            'path'    => parent::getRootNamespace()
        ]);
    }
}

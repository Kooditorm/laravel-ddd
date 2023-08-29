<?php
/**
 * Author: oswin
 * Time: 2021/11/13-13:07
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Console\Makers;

use App\Infrastructure\Libraries\Prettus\Generator;
use App\Infrastructure\Console\Makers\Traits\FieldTrait;
use Illuminate\Support\Str;

class ModelGenerator extends Generator
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
    protected $stub = 'model';

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
        return '';
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

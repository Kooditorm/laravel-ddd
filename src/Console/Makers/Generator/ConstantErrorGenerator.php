<?php

namespace DDDCore\Console\Makers\Generator;

class ConstantErrorGenerator extends ConstantGenerator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'constant/error-constant';

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/'.config('repository.generator.inputPath').'ErrorConstant.php';
    }
}

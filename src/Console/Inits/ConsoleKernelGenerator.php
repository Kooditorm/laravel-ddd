<?php

namespace DDDCore\Console\Inits;

use DDDCore\Libraries\Prettus\Generator;

/**
 * @class ConsoleKernelGenerator
 * @package DDDCore\Console\Inits
 */
class ConsoleKernelGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'console/kernel';

    /**
     * Create new instance of this class.
     *
     * @param  array  $options
     */
    public function __construct(array $options = [])
    {
        config([
            'repository.generator.stubsOverridePath' => __dir__,
            'repository.generator.rootNamespace'     => ' App\Interfaces\Console',
        ]);
        $options['name'] = 'Kernel';
        parent::__construct($options);
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'app/Interfaces/Console';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/Kernel.php';
    }
}

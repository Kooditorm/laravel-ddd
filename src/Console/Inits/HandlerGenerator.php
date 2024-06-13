<?php

namespace DDDCore\Console\Inits;

use DDDCore\Libraries\Prettus\Generator;

/**
 * @class HandlerGenerator
 * @package DDDCore\Console\Inits
 */
class HandlerGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'Handler';

    /**
     * Create new instance of this class.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        config([
            'repository.generator.stubsOverridePath' => __dir__,
            'repository.generator.rootNamespace' => 'App\Exceptions',
        ]);
        $options['name'] = 'Handler';
        parent::__construct($options);
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'app/Exceptions';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/Handler.php';
    }
}

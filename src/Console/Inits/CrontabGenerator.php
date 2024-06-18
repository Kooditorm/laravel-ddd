<?php

namespace DDDCore\Console\Inits;

use DDDCore\Libraries\Prettus\Generator;

/**
 * @class CrontabGenerator
 * @package DDDCore\Console\Inits
 */
class CrontabGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'console/console/crontab.stub';

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
        parent::__construct($options);
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'app/Interfaces/Console/Commands';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath().'/'.$this->getPathConfigNode().'/'.$this->getName().'Command.php';
    }
}

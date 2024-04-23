<?php

namespace DDDCore\Libraries\Prettus;

use DDDCore\Traits\FieldTrait;
use Prettus\Repository\Generators\FileAlreadyExistsException;
use Prettus\Repository\Generators\Generator as PretTusGenerator;

abstract class Generator extends PrettusGenerator
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
     * Run the generator.
     *
     * @return int
     * @throws FileAlreadyExistsException
     */
    public function run(): int
    {
        $this->setUp();
        $action = $this->options['action'] ?? '';

        return parent::run();
    }
}

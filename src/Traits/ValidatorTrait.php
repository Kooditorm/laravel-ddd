<?php

namespace DDDCore\Traits;

/**
 * @trait ValidatorTrait
 * @package DDDCore\Traits
 */
trait ValidatorTrait
{

    public function passes($action = null): bool
    {
        $isValidator = true;
    }
}

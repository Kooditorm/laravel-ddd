<?php

namespace DDDCore\Exceptions;

use Exception;
use Throwable;

class BaseException extends Exception
{

    public function __construct(array $error, Throwable $previous = null)
    {
        parent::__construct($error[array_key_first($error)], array_key_first($error),$previous);
    }
}

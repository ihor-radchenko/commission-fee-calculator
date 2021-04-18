<?php

namespace App\Exception;

use Throwable;
use LogicException;

class FactoryLogicException extends LogicException
{
    public function __construct($className, $code = 0, Throwable $previous = null)
    {
        $message = "Can't instantiate instance of {$className}.";

        parent::__construct($message, $code, $previous);
    }
}
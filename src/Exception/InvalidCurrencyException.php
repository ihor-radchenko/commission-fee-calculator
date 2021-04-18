<?php

namespace App\Exception;

use Throwable;
use App\Entity\Currency;
use InvalidArgumentException;

class InvalidCurrencyException extends InvalidArgumentException
{
    public function __construct(Currency $currency, $code = 0, Throwable $previous = null)
    {
        $message = "Invalid currency {$currency}.";

        parent::__construct($message, $code, $previous);
    }
}
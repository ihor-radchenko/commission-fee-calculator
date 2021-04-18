<?php

namespace App\Entity;

class Currency
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = strtoupper($code);
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function isSame(Currency $otherCurrency): bool
    {
        return $this->code === $otherCurrency->code;
    }
}
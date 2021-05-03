<?php

namespace App\Entity;

use App\Factory\ConfigFactory;

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

    public function getPrecision(): int
    {
        return ConfigFactory::create()->get("currencies.precision.{$this}")
            ?? ConfigFactory::create()->get('currencies.precision.default');
    }

    public function isSame(Currency $otherCurrency): bool
    {
        return $this->code === $otherCurrency->code;
    }
}
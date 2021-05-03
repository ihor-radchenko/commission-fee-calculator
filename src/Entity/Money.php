<?php

namespace App\Entity;

class Money
{
    private $amount;

    private $currency;

    public function __construct(string $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function __toString(): string
    {
        $precision = $this->getCurrency()->getPrecision();
        $decimals = 10 ** $precision;
        $amount = ceil($this->amount * $decimals) / $decimals;

        return number_format($amount, $precision, '.', '');
    }
}
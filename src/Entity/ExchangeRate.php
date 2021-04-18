<?php

namespace App\Entity;

class ExchangeRate
{
    private $from;

    private $to;

    private $rate;

    public function __construct(Currency $from, Currency $to, string $rate)
    {
        $this->from = $from;
        $this->to = $to;
        $this->rate = $rate;
    }

    public function getFromCurrency(): Currency
    {
        return $this->from;
    }

    public function getToCurrency(): Currency
    {
        return $this->to;
    }

    public function __toString(): string
    {
        return $this->rate;
    }
}

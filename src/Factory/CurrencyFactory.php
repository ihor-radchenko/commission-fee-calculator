<?php

namespace App\Factory;

use App\Entity\Currency;

class CurrencyFactory
{
    public static function createBase(): Currency
    {
        return new Currency(ConfigFactory::create()->get('currencies.base'));
    }
}

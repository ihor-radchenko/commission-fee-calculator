<?php

namespace App\Factory;

use App\Contract\Service\Exchanger as ExchangerContract;
use App\Service\Exchanger;

class ExchangerFactory
{
    public static function create(): ExchangerContract
    {
        return new Exchanger(ExchangeRateRepositoryFactory::create(), MathFactory::create());
    }
}

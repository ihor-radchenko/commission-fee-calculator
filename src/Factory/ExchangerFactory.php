<?php

namespace App\Factory;

use App\Service\Exchanger;
use App\Contract\Service\Exchanger as ExchangerContract;

class ExchangerFactory
{
    public static function create(): ExchangerContract
    {
        return new Exchanger(ExchangeRateRepositoryFactory::create(), MathFactory::create());
    }
}

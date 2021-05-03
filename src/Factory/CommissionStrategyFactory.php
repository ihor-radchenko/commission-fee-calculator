<?php

namespace App\Factory;

use App\Entity\Money;
use App\Entity\Operation;
use App\Contract\CommissionStrategy;
use App\Exception\FactoryLogicException;
use App\CommissionStrategy\FreeLimitAmount;
use App\CommissionStrategy\PercentCommission;
use App\CommissionStrategy\FindPrevOperation;
use App\CommissionStrategy\FreeLimitOperation;
use App\CommissionStrategy\FreeChargeCommission;

class CommissionStrategyFactory
{
    public static function createFor(Operation $operation): CommissionStrategy
    {
        $factoryMethod = implode('', [
            'create',
            ucfirst($operation->getUser()->getType()),
            ucfirst($operation->getType()),
            'Strategy',
        ]);

        if (method_exists(self::class, $factoryMethod)) {
            return self::{$factoryMethod}($operation);
        }

        throw new FactoryLogicException(CommissionStrategy::class);
    }

    private static function createPrivateDepositStrategy(Operation $operation): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), .0003);
    }

    private static function createPrivateWithdrawStrategy(Operation $operation): CommissionStrategy
    {
        ($freeCommissionStrategy = new FindPrevOperation($operation, 'this week'))
            ->setNext(new FreeLimitOperation(3))
            ->setNext(new FreeLimitAmount(ExchangerFactory::create(), MathFactory::create(), new Money(1000, CurrencyFactory::createBase())));

        ($strategy = new FreeChargeCommission($freeCommissionStrategy))
            ->setNext(new PercentCommission(MathFactory::create(), .003));

        return $strategy;
    }

    private static function createBusinessDepositStrategy(Operation $operation): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), .0003);
    }

    private static function createBusinessWithdrawStrategy(Operation $operation): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), .005);
    }
}
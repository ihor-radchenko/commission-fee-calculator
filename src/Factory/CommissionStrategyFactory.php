<?php

namespace App\Factory;

use App\CommissionStrategy\FindPrevOperation;
use App\CommissionStrategy\FreeChargeCommission;
use App\CommissionStrategy\FreeLimitAmount;
use App\CommissionStrategy\FreeLimitOperation;
use App\CommissionStrategy\PercentCommission;
use App\Contract\CommissionStrategy;
use App\Entity\Currency;
use App\Entity\Money;
use App\Entity\Operation;
use App\Exception\FactoryLogicException;

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
            $config = ConfigFactory::create()
                ->get("commission.strategy.{$operation->getUser()->getType()}.{$operation->getType()}");

            return self::{$factoryMethod}($operation, $config);
        }

        throw new FactoryLogicException(CommissionStrategy::class);
    }

    private static function createPrivateDepositStrategy(Operation $operation, array $config): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), $config['commission']);
    }

    private static function createPrivateWithdrawStrategy(Operation $operation, array $config): CommissionStrategy
    {
        $freeAmount = new Money($config['free_amount']['amount'], new Currency($config['free_amount']['currency']));

        ($freeCommissionStrategy = new FindPrevOperation($operation, $config['free_period']))
            ->setNext(new FreeLimitOperation($config['free_limit_operation']))
            ->setNext(new FreeLimitAmount(ExchangerFactory::create(), MathFactory::create(), $freeAmount));

        ($strategy = new FreeChargeCommission($freeCommissionStrategy))
            ->setNext(new PercentCommission(MathFactory::create(), $config['commission']));

        return $strategy;
    }

    private static function createBusinessDepositStrategy(Operation $operation, array $config): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), $config['commission']);
    }

    private static function createBusinessWithdrawStrategy(Operation $operation, array $config): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), $config['commission']);
    }
}

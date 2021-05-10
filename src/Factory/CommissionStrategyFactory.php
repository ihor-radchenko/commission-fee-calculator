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
    /**
     * @description create algorithm for commission calculation.
     */
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

    /**
     * @description private deposit charged 0.03% (configurable by app.commission.strategy.private.deposit.commission)
     * of deposit amount.
     */
    private static function createPrivateDepositStrategy(Operation $operation, array $config): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), $config['commission']);
    }

    /**
     * @description private withdraw charged 0.3% (configurable by app.commission.strategy.private.withdraw.commission)
     * of withdraw amount.
     *
     * For this rule there are privileges for charging a commission (implemented by $freeCommissionStrategy):
     *
     * 1000.00 EUR for a week (from Monday to Sunday) is free of charge.
     * Only for the first 3 withdraw operations per a week.
     * 4th and the following operations are calculated by using the rule above (0.3%).
     * If total free of charge amount is exceeded them commission is calculated only for the exceeded amount
     * (i.e. up to 1200.00 EUR commission fee is charged only from 200.00 EUR).
     * configurable by:
     *  - app.commission.strategy.private.withdraw.free_amount (1000.00 EUR);
     *  - app.commission.strategy.private.withdraw.free_period (week (from Monday to Sunday));
     *  - app.commission.strategy.private.withdraw.free_limit_operation (first 3 withdraw operations).
     */
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

    /**
     * @description business deposit charged 0.03% (configurable by app.commission.strategy.business.deposit.commission)
     * of deposit amount.
     */
    private static function createBusinessDepositStrategy(Operation $operation, array $config): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), $config['commission']);
    }

    /**
     * @description business withdraw charged 0.5% (configurable by app.commission.strategy.business.withdraw.commission)
     * of withdraw amount.
     */
    private static function createBusinessWithdrawStrategy(Operation $operation, array $config): CommissionStrategy
    {
        return new PercentCommission(MathFactory::create(), $config['commission']);
    }
}

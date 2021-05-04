<?php

namespace App\Tests\CommissionStrategy;

use App\CommissionStrategy\FreeLimitOperation;
use App\Contract\CommissionStrategy;
use App\Entity\Currency;
use App\Entity\Money;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class FreeLimitOperationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @dataProvider operationCountLessThanFreeLimitDataProvider
     */
    public function testApplyFreeIfOperationsCountLessThanLimit($freeLimit, $operation, $previous): void
    {
        $spy = Mockery::spy(CommissionStrategy::class);

        $strategy = new FreeLimitOperation($freeLimit);

        $strategy->setNext($spy);

        $strategy->execute($operation, $previous);

        $spy->shouldHaveReceived('execute')
            ->once();
    }

    /**
     * @dataProvider operationCountGreaterThanFreeLimitDataProvider
     */
    public function testDontApplyFreeIfOperationsCountGreaterThanLimit($freeLimit, $operation, $previous): void
    {
        $spy = Mockery::spy(CommissionStrategy::class);

        $strategy = new FreeLimitOperation($freeLimit);

        $strategy->setNext($spy);

        $strategy->execute($operation, $previous);

        $spy->shouldNotHaveReceived('execute');
    }

    public function operationCountLessThanFreeLimitDataProvider(): array
    {
        return [
            [3, new Money(10, new Currency('eur')), array_fill(0, 2, new Money(100, new Currency('eur')))],
            [5, new Money(10, new Currency('usd')), array_fill(0, 3, new Money(100, new Currency('eur')))],
            [5, new Money(10, new Currency('jpy')), array_fill(0, 4, new Money(100, new Currency('eur')))],
        ];
    }

    public function operationCountGreaterThanFreeLimitDataProvider(): array
    {
        return [
            [3, new Money(10, new Currency('eur')), array_fill(0, 3, new Money(100, new Currency('eur')))],
            [5, new Money(10, new Currency('usd')), array_fill(0, 6, new Money(100, new Currency('eur')))],
            [5, new Money(10, new Currency('jpy')), array_fill(0, 7, new Money(100, new Currency('eur')))],
        ];
    }
}

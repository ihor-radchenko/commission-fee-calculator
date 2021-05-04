<?php

namespace App\Tests\CommissionStrategy;

use App\CommissionStrategy\PercentCommission;
use App\Entity\Currency;
use App\Entity\Money;
use App\Service\BcMath;
use PHPUnit\Framework\TestCase;

class PercentCommissionTest extends TestCase
{
    private $math;

    protected function setUp(): void
    {
        parent::setUp();

        $this->math = new BcMath(2);
    }

    /**
     * @dataProvider commissionDataProvider
     */
    public function testPercentCommissionCalculation($operation, $commission, $expected): void
    {
        $strategy = new PercentCommission($this->math, $commission);

        $result = $strategy->execute($operation);

        $this->assertInstanceOf(Money::class, $result);
        $this->assertEquals($expected, $result);
    }

    public function commissionDataProvider(): array
    {
        return [
            [new Money(1000, new Currency('eur')), .5, '500.00'],
            [new Money(1000, new Currency('eur')), .05, '50.00'],
            [new Money(1000, new Currency('eur')), .005, '5.00'],
            [new Money(1000, new Currency('eur')), .0005, '0.50'],
        ];
    }
}

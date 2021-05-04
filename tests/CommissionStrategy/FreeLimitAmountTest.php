<?php

namespace App\Tests\CommissionStrategy;

use App\CommissionStrategy\FreeLimitAmount;
use App\Contract\Repository\ExchangeRateRepository;
use App\Entity\Currency;
use App\Entity\ExchangeRate;
use App\Entity\Money;
use App\Repository\CacheProxyExchangeRateRepository;
use App\Service\BcMath;
use App\Service\Exchanger;
use Mockery;
use PHPUnit\Framework\TestCase;

class FreeLimitAmountTest extends TestCase
{
    /**
     * @dataProvider freeLimitAmountDataProvider
     */
    public function testApplyFreeDiscountBeforeChargeCommission($operation, $free, $previous, $rates, $expected): void
    {
        $mockExchangeRates = Mockery::mock(ExchangeRateRepository::class);

        if ($rates) {
            $mockExchangeRates->shouldReceive('getExchangeRate')
                ->andReturn(...$rates);
        }

        $strategy = new FreeLimitAmount(new Exchanger(new CacheProxyExchangeRateRepository($mockExchangeRates), new BcMath(3)), new BcMath(3), $free);

        $result = $strategy->execute($operation, $previous);

        $this->assertInstanceOf(Money::class, $result);
        $this->assertEquals((string) $expected, (string) $result);
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }

    public function freeLimitAmountDataProvider(): array
    {
        return [
            [
                new Money(1000, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [],
                [],
                new Money(0, new Currency('eur')),
            ],
            [
                new Money(1200, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [],
                [],
                new Money(200, new Currency('eur')),
            ],
            [
                new Money(1000, new Currency('eur')),
                new Money(0, new Currency('eur')),  // freeLimit
                [],
                [],
                new Money(1000, new Currency('eur')),
            ],

            [
                new Money(2000, new Currency('usd')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2')
                ],
                new Money(0, new Currency('usd')),
            ],
            [
                new Money(2200, new Currency('usd')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2')
                ],
                new Money(200, new Currency('usd')),
            ],

            [
                new Money(200, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(500, new Currency('eur')),
                ],
                [],
                new Money(0, new Currency('eur')),
            ],
            [
                new Money(200, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(1000, new Currency('eur')),
                ],
                [],
                new Money(200, new Currency('eur')),
            ],
            [
                new Money(200, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(1000, new Currency('usd')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2')
                ],
                new Money(0, new Currency('eur')),
            ],
            [
                new Money(200, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(1000, new Currency('usd')),
                    new Money(500, new Currency('usd')),
                    new Money(100, new Currency('usd')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2')
                ],
                new Money(0, new Currency('eur')),
            ],
            [
                new Money(200, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(1000, new Currency('usd')),
                    new Money(500, new Currency('usd')),
                    new Money(100, new Currency('usd')),
                    new Money(200, new Currency('usd')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2')
                ],
                new Money(100, new Currency('eur')),
            ],
            [
                new Money(300, new Currency('usd')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(1000, new Currency('usd')),
                    new Money(500, new Currency('eur')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2'),
                ],
                new Money(300, new Currency('usd')),
            ],
            [
                new Money(300, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(900, new Currency('usd')),
                    new Money(500, new Currency('eur')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2'),
                ],
                new Money(250, new Currency('eur')),
            ],
            [
                new Money(300, new Currency('usd')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(900, new Currency('usd')),
                    new Money(450, new Currency('eur')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2'),
                ],
                new Money(100, new Currency('usd')),
            ],

            [
                new Money(300, new Currency('usd')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(900, new Currency('usd')),
                    new Money(4500, new Currency('jpy')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2'),
                    new ExchangeRate(new Currency('jpy'), new Currency('eur'), '10'),
                ],
                new Money(100, new Currency('usd')),
            ],
            [
                new Money(300, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(900, new Currency('usd')),
                    new Money(4500, new Currency('jpy')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2'),
                    new ExchangeRate(new Currency('jpy'), new Currency('eur'), '10'),
                ],
                new Money(200, new Currency('eur')),
            ],
            [
                new Money(3000, new Currency('jpy')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(800, new Currency('eur')),
                ],
                [
                    new ExchangeRate(new Currency('jpy'), new Currency('eur'), '10'),
                ],
                new Money(1000, new Currency('jpy')),
            ],

            [
                new Money(600, new Currency('eur')),
                new Money(1000, new Currency('eur')),  // freeLimit
                [
                    new Money(50, new Currency('usd')),
                    new Money(2500, new Currency('jpy')),
                    new Money(100, new Currency('eur')),
                    new Money(800, new Currency('jpy')),
                ],
                [
                    new ExchangeRate(new Currency('usd'), new Currency('eur'), '2'),
                    new ExchangeRate(new Currency('jpy'), new Currency('eur'), '10'),
                ],
                new Money(55, new Currency('eur')),
            ],
        ];
    }
}

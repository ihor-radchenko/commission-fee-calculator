<?php

namespace App\Tests\Service;

use App\Contract\Repository\ExchangeRateRepository;
use App\Entity\Currency;
use App\Entity\ExchangeRate;
use App\Entity\Money;
use App\Service\BcMath;
use App\Service\Exchanger;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ExchangerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $mockRates;

    private $exchanger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockRates = Mockery::mock(ExchangeRateRepository::class);

        $this->exchanger = new Exchanger($this->mockRates, new BcMath(3));
    }

    /**
     * @dataProvider exchangeRatesDataProvider
     */
    public function testExchanger($money, $toCurrency, $rate, $expected): void
    {
        $this->mockRates->shouldReceive('getExchangeRate')
            ->andReturn(new ExchangeRate($money->getCurrency(), $toCurrency, $rate));

        $result = $this->exchanger->exchange($money, $toCurrency);

        $this->assertInstanceOf(Money::class, $result);
        $this->assertEquals($expected, $result);
    }

    public function exchangeRatesDataProvider(): array
    {
        $eur = new Currency('eur');
        $usd = new Currency('usd');
        $jpy = new Currency('jpy');

        return [
            [new Money(1000, $usd), $eur, '2.00', '500.00'],
            [new Money(1000, $eur), $usd, '2.00', '2000.00'],
            [new Money(1000, $eur), $jpy, '8.00', '8000.00'],
            [new Money(1000, $jpy), $eur, '8.00', '125.00'],
        ];
    }
}

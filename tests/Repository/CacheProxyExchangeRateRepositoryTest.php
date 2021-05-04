<?php

namespace App\Tests\Repository;

use App\Contract\Repository\ExchangeRateRepository;
use App\Entity\Currency;
use App\Repository\CacheProxyExchangeRateRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class CacheProxyExchangeRateRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $spyRepository;

    private $cacheProxy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spyRepository = Mockery::spy(ExchangeRateRepository::class);

        $this->cacheProxy = new CacheProxyExchangeRateRepository($this->spyRepository);
    }

    /**
     * @dataProvider argumentDataProvider
     */
    public function testCallRepositoryWithCacheProxyOnlyOnce($calls, $expectedTimes): void
    {
        foreach ($calls as $arguments) {
            $this->cacheProxy->getExchangeRate(...$arguments);
        }

        $this->spyRepository->shouldHaveReceived('getExchangeRate')
            ->times($expectedTimes);
    }

    public function argumentDataProvider(): array
    {
        return [
            [
                [
                    [new Currency('usd'), new Currency('eur')],
                    [new Currency('usd'), new Currency('eur')]
                ],
                1
            ],
            [
                [
                    [new Currency('usd'), new Currency('eur')],
                    [new Currency('usd'), new Currency('eur')],
                    [new Currency('jpy'), new Currency('eur')]
                ],
                2
            ]
        ];
    }
}

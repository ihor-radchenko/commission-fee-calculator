<?php

namespace App\Repository;

use GuzzleHttp\Client;
use App\Entity\Currency;
use App\Entity\ExchangeRate;
use App\Exception\InvalidCurrencyException;
use App\Contract\Repository\ExchangeRateRepository;

class ApiExchangeRateRepository implements ExchangeRateRepository
{
    private $client;

    private $accessKey;

    public function __construct(Client $client, string $accessKey)
    {
        $this->client = $client;
        $this->accessKey = $accessKey;
    }

    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate
    {
        $response = $this->client->get('latest', [
            'query' => [
                'access_key' => $this->accessKey,
                'symbols'    => (string) $fromCurrency,
//                'base'       => (string) $toCurrency,
            ],
        ])->getBody()->getContents();

        $exchangeRates = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($exchangeRates['success']) {
            $rate = $exchangeRates['rates'][(string) $fromCurrency];

            return new ExchangeRate(new Currency($exchangeRates['base']), $fromCurrency, $rate);
        }

        throw new InvalidCurrencyException($fromCurrency);
    }
}

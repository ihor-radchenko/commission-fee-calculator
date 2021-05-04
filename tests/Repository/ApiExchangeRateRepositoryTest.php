<?php

namespace App\Tests\Repository;

use App\Entity\Currency;
use App\Entity\ExchangeRate;
use App\Repository\ApiExchangeRateRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ApiExchangeRateRepositoryTest extends TestCase
{
    private $repository;

    private $history = [];

    protected function setUp(): void
    {
        parent::setUp();

        $mock = new MockHandler([
            new Response(200, [], '{"success":true,"timestamp":1620138543,"base":"EUR","date":"2021-05-04","rates":{"AED":4.416623,"AFN":93.249662,"ALL":123.160953,"AMD":626.332583,"ANG":2.157985,"AOA":786.766429,"ARS":112.692353,"AUD":1.558797,"AWG":2.165614,"AZN":2.044637,"BAM":1.957508,"BBD":2.427321,"BDT":101.941439,"BGN":1.956786,"BHD":0.453357,"BIF":2355.600714,"BMD":1.202451,"BND":1.60557,"BOB":8.28897,"BRL":6.560809,"BSD":1.202095,"BTC":2.1704745e-5,"BTN":88.780276,"BWP":13.09546,"BYN":3.08732,"BYR":23568.031648,"BZD":2.423197,"CAD":1.480644,"CDF":2406.103188,"CHF":1.097032,"CLF":0.030762,"CLP":849.174542,"CNY":7.784423,"COP":4615.005381,"CRC":740.803364,"CUC":1.202451,"CUP":31.864941,"CVE":110.685651,"CZK":25.844233,"DJF":214.013426,"DKK":7.435895,"DOP":68.485509,"DZD":160.642467,"EGP":18.840323,"ERN":18.039057,"ETB":50.562965,"EUR":1,"FJD":2.442538,"FKP":0.87343,"GBP":0.867346,"GEL":4.154451,"GGP":0.87343,"GHS":6.956174,"GIP":0.87343,"GMD":61.535382,"GNF":11925.298825,"GTQ":9.280484,"GYD":251.514522,"HKD":9.339854,"HNL":29.02113,"HRK":7.541171,"HTG":104.442475,"HUF":360.183247,"IDR":17390.622088,"ILS":3.925557,"IMP":0.87343,"INR":88.735081,"IQD":1758.583994,"IRR":50629.182568,"ISK":148.996068,"JEP":0.87343,"JMD":183.859169,"JOD":0.852496,"JPY":131.297379,"KES":128.963161,"KGS":101.957471,"KHR":4871.127119,"KMF":492.522432,"KPW":1082.205766,"KRW":1354.055869,"KWD":0.362534,"KYD":1.001775,"KZT":515.021414,"LAK":11318.082165,"LBP":1828.312604,"LKR":236.840409,"LRD":206.821362,"LSL":17.375884,"LTL":3.550524,"LVL":0.72735,"LYD":5.398615,"MAD":10.728865,"MDL":21.398562,"MGA":4509.189191,"MKD":61.607716,"MMK":1872.481797,"MNT":3427.768653,"MOP":9.618924,"MRO":429.274655,"MUR":48.516883,"MVR":18.457885,"MWK":952.943413,"MXN":24.335919,"MYR":4.957705,"MZN":69.2494,"NAD":17.375793,"NGN":458.217708,"NIO":42.266283,"NOK":10.012301,"NPR":142.051838,"NZD":1.680755,"OMR":0.462941,"PAB":1.202095,"PEN":4.589157,"PGK":4.250653,"PHP":57.739272,"PKR":184.100339,"PLN":4.559356,"PYG":7935.067979,"QAR":4.3781,"RON":4.92789,"RSD":117.7259,"RUB":90.047078,"RWF":1183.81261,"SAR":4.509421,"SBD":9.577502,"SCR":18.060469,"SDG":471.360877,"SEK":10.178984,"SGD":1.607051,"SHP":0.87343,"SLL":12313.093833,"SOS":704.635807,"SRD":17.019493,"STD":24925.764345,"SVC":10.518709,"SYP":1512.162678,"SZL":17.375431,"THB":37.492256,"TJS":13.710851,"TMT":4.208577,"TND":3.299476,"TOP":2.723313,"TRY":9.994673,"TTD":8.155225,"TWD":33.652385,"TZS":2788.482856,"UAH":33.438381,"UGX":4273.762949,"USD":1.202451,"UYU":52.672926,"UZS":12655.792337,"VEF":257120277932.11307,"VND":27728.510705,"VUV":131.715071,"WST":3.044277,"XAF":656.552478,"XAG":0.044747,"XAU":0.00067,"XCD":3.249683,"XDR":0.838561,"XOF":654.734044,"XPF":119.343342,"YER":301.123723,"ZAR":17.398209,"ZMK":10823.499754,"ZMW":26.867846,"ZWL":387.189326}}')
        ]);

        $handler = HandlerStack::create($mock);

        $handler->push(Middleware::history($this->history));

        $client = new Client(['handler' => $handler]);

        $this->repository = new ApiExchangeRateRepository($client, 'test-key');
    }

    public function testRepositoryMakeRequestToLatestEndpointWithAccessKey(): void
    {
        $this->repository->getExchangeRate(new Currency('eur'), new Currency('usd'));

        $this->assertCount(1, $this->history);

        /** @var \GuzzleHttp\Psr7\Request $request */
        $request = reset($this->history)['request'];

        $this->assertSame('GET', $request->getMethod());
        $this->assertStringEndsWith('latest', $request->getUri()->getPath());
        $this->assertStringStartsWith('access_key=test-key', $request->getUri()->getQuery());
        $this->assertStringEndsWith('symbols=EUR', $request->getUri()->getQuery());
    }

    /**
     * @dataProvider argumentDataProvider
     */
    public function testGetExchangeRateEntityFromApi($to, $from, $expectedRate): void
    {
        $rate = $this->repository->getExchangeRate($from, $to);

        $this->assertInstanceOf(ExchangeRate::class, $rate);

        $this->assertEquals($expectedRate, $rate);
    }

    public function argumentDataProvider(): array
    {
        return [
            'USD to EUR' => [new Currency('eur'), new Currency('usd'), '1.202451'],
            'JPY to EUR' => [new Currency('eur'), new Currency('jpy'), '131.297379'],
        ];
    }
}

<?php

namespace App\Tests\CommissionStrategy;

use App\CommissionStrategy\FindPrevOperation;
use App\CommissionStrategy\Strategy;
use App\Entity\Currency;
use App\Entity\Money;
use App\Entity\Operation;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FindPrevOperationTest extends TestCase
{
    /**
     * @dataProvider operationDataProvider
     */
    public function testFindPrevOperation($operation, $expectedPrevCount, $date): void
    {
        $strategy = new FindPrevOperation($operation, $date);

        $spy = new class ($this, $expectedPrevCount) extends Strategy {
            private $phpUnit;

            private $expectedCount;

            public function __construct(TestCase $phpUnit, $expectedCount)
            {
                $this->phpUnit = $phpUnit;
                $this->expectedCount = $expectedCount;
            }

            public function execute(Money $money, array $operations = []): Money
            {
                $this->phpUnit->assertCount($this->expectedCount, $operations);

                return parent::execute($money, $operations);
            }
        };

        $strategy->setNext($spy);

        $strategy->execute($operation->getMoney());
    }

    public function operationDataProvider(): array
    {
        $operations = [
            [
                [
                    '2021-05-02,1,private,withdraw,30000,JPY',
                    '2021-05-03,1,private,withdraw,1000.00,EUR',
                ],
                0,
                'this week'
            ],
            [
                [
                    '2021-05-02,1,private,withdraw,30000,JPY',
                    '2021-05-03,1,private,withdraw,1000.00,EUR',
                    '2021-05-04,1,private,withdraw,100.00,USD',
                    '2021-05-05,1,private,withdraw,100.00,EUR',
                ],
                2,
                'this week',
            ],
            [
                [
                    '2021-05-02,1,private,withdraw,30000,JPY',
                    '2021-05-03,1,private,withdraw,1000.00,EUR',
                    '2021-05-04,1,private,withdraw,100.00,USD',
                    '2021-05-05,1,private,withdraw,100.00,EUR',
                ],
                3,
                'first day of this month',
            ],
            [
                [
                    '2021-04-23,1,private,withdraw,30000,JPY',
                    '2021-04-27,1,private,withdraw,1000.00,EUR',
                    '2021-05-01,1,private,withdraw,100.00,USD',
                    '2021-05-02,1,private,withdraw,100.00,EUR',
                ],
                2,
                'this week',
            ],
            [
                [
                    '2021-04-02,1,private,withdraw,30000,JPY',
                    '2021-04-11,1,private,withdraw,1000.00,EUR',
                    '2021-04-19,1,private,withdraw,100.00,USD',
                    '2021-04-29,1,private,withdraw,100.00,EUR',
                ],
                3,
                'first day of this month',
            ],
            [
                [
                    '2021-04-02,1,private,withdraw,30000,JPY',
                    '2021-04-11,1,private,withdraw,1000.00,EUR',
                    '2021-04-19,1,private,withdraw,100.00,USD',
                    '2021-04-29,1,private,withdraw,100.00,EUR',
                    '2021-05-01,1,private,withdraw,250.00,USD',
                ],
                0,
                'first day of this month',
            ],
        ];

        return array_map(static function ($args) {
            $prev = null;

            foreach ($args[0] as $operationRaw) {
                $operation = explode(',', $operationRaw);

                $date = DateTimeImmutable::createFromFormat('Y-m-d', $operation[0]);
                $user = new User($operation[1], $operation[2]);
                $money = new Money($operation[4], new Currency($operation[5]));

                $operationItem = new Operation($date, $operation[3], $user, $money, $prev);

                $prev = $operationItem;
            }

            return [$operationItem, $args[1], $args[2]];
        }, $operations);
    }
}

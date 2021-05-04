<?php

namespace App\Tests\Command;

use App\Command\CalculateCommissionFee;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CalculateCommissionFeeTest extends TestCase
{
    /**
     * @dataProvider commandDataProvider
     */
    public function testAppCommand($command, $arguments, $expectedOutput): void
    {
        $tester = new CommandTester($command);
        $tester->execute($arguments);

        $output = $tester->getDisplay();

        $this->assertEquals($expectedOutput, $output);
    }

    public function commandDataProvider(): array
    {
        return [
            'calculate commission fee' => [
                new CalculateCommissionFee(),
                ['csv_path' => 'storage/input.csv'],
                implode(PHP_EOL, [
                    '0.60',
                    '3.00',
                    '0.00',
                    '0.06',
                    '1.50',
                    '0',
                    '0.70',
                    '0.30',
                    '0.30',
                    '3.00',
                    '0.00',
                    '0.00',
                    '8612',
                ]) . PHP_EOL,
            ]
        ];
    }
}

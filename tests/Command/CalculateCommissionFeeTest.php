<?php

namespace App\Tests\Command;

use App\Command\CalculateCommissionFee;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CalculateCommissionFeeTest extends TestCase
{
    public function testCalculateCommissionCommand(): void
    {
        $tester = new CommandTester(new CalculateCommissionFee());

        $tester->execute(['csv_path' => 'storage/input.csv']);

        $expectedOutput = implode(PHP_EOL, [
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
            ]) . PHP_EOL;

        $output = $tester->getDisplay();

        $this->assertEquals($expectedOutput, $output);
    }
}

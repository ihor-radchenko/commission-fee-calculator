<?php

namespace App\Tests\Service;

use App\Entity\Currency;
use App\Entity\Money;
use App\Service\BcMath;
use PHPUnit\Framework\TestCase;

class BcMathTest extends TestCase
{
    private $math;

    protected function setUp(): void
    {
        parent::setUp();

        $this->math = new BcMath(2);
    }

    /**
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd($leftOperand, $rightOperand, $expectation): void
    {
        $this->assertEquals($expectation, $this->math->add($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider dataProviderForSubTesting
     */
    public function testSub($leftOperand, $rightOperand, $expectation): void
    {
        $this->assertEquals($expectation, $this->math->sub($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider dataProviderForMulTesting
     */
    public function testMul($leftOperand, $rightOperand, $expectation): void
    {
        $this->assertEquals($expectation, $this->math->mul($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider dataProviderForDivTesting
     */
    public function testDiv($leftOperand, $rightOperand, $expectation): void
    {
        $this->assertEquals($expectation, $this->math->div($leftOperand, $rightOperand));
    }

    public function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers' => ['1', '2', '3.00'],
            'add negative number to a positive' => ['-1', '2', '1.00'],
            'add natural number to a float' => ['1', '1.05123', '2.05'],
            'add 2 money item' => [new Money('10', new Currency('eur')), new Money('15', new Currency('eur')), '25.00'],
            'add positive number to money' => ['15', new Money('9', new Currency('eur')), '24.00'],
            'add negative number to money' => ['-3', new Money('7', new Currency('eur')), '4.00'],
            'add float number to money' => ['15.689', new Money('10', new Currency('eur')), '25.68'],
        ];
    }

    public function dataProviderForSubTesting(): array
    {
        return [
            'sub 2 natural numbers' => ['3', '2', '1.00'],
            'sub negative number from a positive' => ['-1', '2', '-3.00'],
            'sub natural number from a float' => ['1', '1.05123', '-0.05'],
            'sub 2 money item' => [new Money('10', new Currency('eur')), new Money('15', new Currency('eur')), '-5.00'],
            'sub positive number from money' => [new Money('9', new Currency('eur')), '15', '-6.00'],
            'sub negative number from money' => [new Money('7', new Currency('eur')), '-3', '10.00'],
            'sub float number from money' => [new Money('10', new Currency('eur')), '15.689', '-5.68'],
        ];
    }

    public function dataProviderForMulTesting(): array
    {
        return [
            'mul 2 natural numbers' => ['1', '2', '2.00'],
            'mul negative number to a positive' => ['-1', '2', '-2.00'],
            'mul natural number to a float' => ['1', '1.05123', '1.05'],
            'mul 2 money item' => [new Money('10', new Currency('eur')), new Money('15', new Currency('eur')), '150.00'],
            'mul positive number to money' => ['15', new Money('9', new Currency('eur')), '135.00'],
            'mul negative number to money' => ['-3', new Money('7', new Currency('eur')), '-21.00'],
            'mul float number to money' => ['15.689', new Money('10', new Currency('eur')), '156.89'],
        ];
    }

    public function dataProviderForDivTesting(): array
    {
        return [
            'div 2 natural numbers' => ['1', '2', '0.50'],
            'div negative number to a positive' => ['-1', '2', '-0.50'],
            'div natural number to a float' => ['1', '0.5', '2.00'],
            'div 2 money item' => [new Money('15', new Currency('eur')), new Money('10', new Currency('eur')), '1.50'],
            'div positive number to money' => ['15', new Money('3', new Currency('eur')), '5.00'],
            'div negative number to money' => ['-3', new Money('1', new Currency('eur')), '-3.00'],
            'div float number to money' => ['20.4', new Money('10', new Currency('eur')), '2.04'],
        ];
    }
}

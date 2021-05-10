<?php

namespace App\Contract\Service;

interface Math
{
    /**
     * @description calculating the sum of two numbers.
     */
    public function add(string $leftOperand, string $rightOperand): string;

    /**
     * @description calculating the difference of two numbers.
     */
    public function sub(string $leftOperand, string $rightOperand): string;

    /**
     * @description calculating the product of two numbers.
     */
    public function mul(string $leftOperand, string $rightOperand): string;

    /**
     * @description calculating division of two numbers.
     */
    public function div(string $leftOperand, string $rightOperand): string;
}

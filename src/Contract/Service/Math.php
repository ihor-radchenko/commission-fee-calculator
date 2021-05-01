<?php

namespace App\Contract\Service;

interface Math
{
    public function add(string $leftOperand, string $rightOperand): string;

    public function sub(string $leftOperand, string $rightOperand): string;

    public function mul(string $leftOperand, string $rightOperand): string;

    public function div(string $leftOperand, string $rightOperand): string;
}

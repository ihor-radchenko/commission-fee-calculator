<?php

namespace App\Service;

use App\Contract\Service\Math;

class BcMath implements Math
{
    private $scale;

    public function __construct(int $scale)
    {
        $this->scale = $scale;
    }

    /**
     * {@inheritDoc}
     */
    public function add(string $leftOperand, string $rightOperand): string
    {
        return bcadd($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * {@inheritDoc}
     */
    public function sub(string $leftOperand, string $rightOperand): string
    {
        return bcsub($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * {@inheritDoc}
     */
    public function mul(string $leftOperand, string $rightOperand): string
    {
        return bcmul($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * {@inheritDoc}
     */
    public function div(string $leftOperand, string $rightOperand): string
    {
        return bcdiv($leftOperand, $rightOperand, $this->scale);
    }
}

<?php

namespace App\Contract\Repository;

interface OperationRepository
{
    public function each(callable $callable): void;
}

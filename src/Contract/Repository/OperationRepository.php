<?php

namespace App\Contract\Repository;

interface OperationRepository
{
    /**
     * @description handle each operation in repository.
     */
    public function each(callable $callable): void;
}

<?php

namespace App\CommissionStrategy;

use App\Entity\Money;
use App\Entity\Operation;
use DateTimeImmutable;

class FindPrevOperation extends Strategy
{
    private $operation;

    private $from;

    public function __construct(Operation $operation, string $from)
    {
        $this->operation = $operation;
        $this->from = $this->getFromDate($operation, $from);
    }

    public function execute(Money $money, array $operations = []): Money
    {
        $operation = $this->operation;

        while ($operation = $this->isDue($operation->getPrev())) {
            $operations[] = $operation->getMoney();
        }

        return parent::execute($money, array_reverse($operations));
    }

    private function getFromDate(Operation $operation, string $from): DateTimeImmutable
    {
        $date = new DateTimeImmutable();

        return $date->setTimestamp(strtotime($from, $operation->getDate()->getTimestamp()));
    }

    private function isDue(?Operation $operation): ?Operation
    {
        if ($operation && $operation->getDate() >= $this->from) {
            return $operation;
        }

        return null;
    }
}

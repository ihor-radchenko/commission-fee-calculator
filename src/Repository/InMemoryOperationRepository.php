<?php

namespace App\Repository;

use App\Contract\OperationDataProvider;
use App\Contract\Repository\OperationRepository;
use App\Entity\Currency;
use App\Entity\Money;
use App\Entity\Operation;
use App\Entity\User;
use DateTimeImmutable;

class InMemoryOperationRepository implements OperationRepository
{
    private $storage;

    public static function initFromDataProvider(OperationDataProvider $dataProvider): OperationRepository
    {
        $repository = new self();

        $previous = [];

        foreach ($dataProvider as $rawOperation) {
            $user = new User($rawOperation['user_id'], $rawOperation['user_type']);
            $money = new Money($rawOperation['amount'], new Currency($rawOperation['currency']));
            $operationDate = DateTimeImmutable::createFromFormat('Y-m-d', $rawOperation['date'])
                ->setTime(0, 0);

            $prevOperation = $previous[$rawOperation['operation_type']][$rawOperation['user_id']] ?? null;

            $operation = new Operation($operationDate, $rawOperation['operation_type'], $user, $money, $prevOperation);

            $repository->storage[] = $operation;

            $previous[$rawOperation['operation_type']][$rawOperation['user_id']] = $operation;
        }

        return $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function each(callable $callable): void
    {
        array_walk($this->storage, $callable);
    }
}

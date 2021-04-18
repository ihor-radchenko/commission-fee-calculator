<?php

namespace App\Factory;

use App\Repository\InMemoryOperationRepository;
use App\Contract\Repository\OperationRepository;

class OperationRepositoryFactory
{
    public static function create(array $args = []): OperationRepository
    {
        return self::createInMemoryRepository($args);
    }

    private static function createInMemoryRepository(array $args): OperationRepository
    {
        $dataProvider = DataProviderFactory::create($args);

        return InMemoryOperationRepository::initFromDataProvider($dataProvider);
    }
}
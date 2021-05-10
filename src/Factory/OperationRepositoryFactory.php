<?php

namespace App\Factory;

use App\Contract\Repository\OperationRepository;
use App\Repository\InMemoryOperationRepository;

class OperationRepositoryFactory
{
    public static function create(array $args = []): OperationRepository
    {
        return self::createInMemoryRepository($args);
    }

    /**
     * @description init in-memory repository from data provider iterator.
     */
    private static function createInMemoryRepository(array $args): OperationRepository
    {
        $dataProvider = DataProviderFactory::create($args);

        return InMemoryOperationRepository::initFromDataProvider($dataProvider);
    }
}

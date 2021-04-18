<?php

namespace App\Factory;

use App\Contract\OperationDataProvider;
use App\Exception\FactoryLogicException;
use App\DataProvider\CsvOperationDataProvider;

class DataProviderFactory
{
    public static function create(array $args = []): OperationDataProvider
    {
        return self::createCsvDataProvider($args);
    }

    private static function createCsvDataProvider(array $args): OperationDataProvider
    {
        if (array_key_exists('csv_path', $args)) {
            return new CsvOperationDataProvider($args['csv_path']);
        }

        throw new FactoryLogicException(CsvOperationDataProvider::class);
    }
}
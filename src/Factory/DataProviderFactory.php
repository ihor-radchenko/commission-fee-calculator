<?php

namespace App\Factory;

use App\Contract\DataProvider;
use App\DataProvider\CsvDataProvider;

class DataProviderFactory
{
    public static function create(array $args): DataProvider
    {
        return new CsvDataProvider(...$args);
    }
}
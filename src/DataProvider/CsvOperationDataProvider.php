<?php

namespace App\DataProvider;

use App\Contract\OperationDataProvider;

class CsvOperationDataProvider implements OperationDataProvider
{
    private $file;

    private $headers;

    private $index;

    public function __construct(string $filePath)
    {
        $this->file = fopen($filePath, 'rb');

        $this->headers = [
            'date',
            'user_id',
            'user_type',
            'operation_type',
            'amount',
            'currency',
        ];
    }

    public function current()
    {
        $row = fgetcsv($this->file);

        return array_combine($this->headers, $row);
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return !feof($this->file);
    }

    public function rewind()
    {
        $this->index = 0;

        rewind($this->file);
    }
}

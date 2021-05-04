<?php

namespace App\Tests\DataProvider;

use App\DataProvider\CsvOperationDataProvider;
use PHPUnit\Framework\TestCase;

class CsvOperationDataProviderTest extends TestCase
{
    private $iterator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->iterator = new CsvOperationDataProvider(__DIR__ . '/../../storage/input.csv');
    }

    public function testIteratorProcessAllRows(): void
    {
        $i = 0;

        foreach ($this->iterator as $_) {
            ++$i;
        }

        $this->assertEquals(13, $i);
    }

    public function testIteratorProvideRightHeaders(): void
    {
        $headers = array_flip([
            'date',
            'user_id',
            'user_type',
            'operation_type',
            'amount',
            'currency',
        ]);

        foreach ($this->iterator as $item) {
            $this->assertCount(0, array_diff_key($item, $headers));
        }
    }
}

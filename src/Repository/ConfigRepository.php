<?php

namespace App\Repository;

use App\Contract\Repository\ConfigRepository as ConfigRepositoryInterface;

class ConfigRepository implements ConfigRepositoryInterface
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function get(string $key, $default = null)
    {
        $segments = explode('.', $key);

        $value = $this->items;

        foreach ($segments as $segment) {
            if (!array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}

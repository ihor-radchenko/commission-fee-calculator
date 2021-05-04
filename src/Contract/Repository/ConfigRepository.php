<?php

namespace App\Contract\Repository;

interface ConfigRepository
{
    public function get(string $key, $default = null);
}

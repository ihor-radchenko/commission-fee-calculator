<?php

namespace App\Factory;

use App\Contract\Repository\ConfigRepository;
use App\Repository\ConfigRepository as Config;

class ConfigFactory
{
    use Singleton;

    protected function createInstance(): ConfigRepository
    {
        $path = [__DIR__, '..', '..', 'config', 'app.php'];

        return new Config(require implode(DIRECTORY_SEPARATOR, $path));
    }
}

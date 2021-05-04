<?php

namespace App\Factory;

use Symfony\Component\Dotenv\Dotenv;
use App\Contract\Repository\ConfigRepository;
use App\Repository\ConfigRepository as Config;

class ConfigFactory
{
    use Singleton;

    protected function createInstance(): ConfigRepository
    {
        $this->loadEnvVariables();

        $path = [__DIR__, '..', '..', 'config', 'app.php'];

        return new Config(require implode(DIRECTORY_SEPARATOR, $path));
    }

    private function loadEnvVariables(): void
    {
        $dotenv = new Dotenv();

        $dotenv->usePutenv()
            ->load(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', '.env']));
    }
}

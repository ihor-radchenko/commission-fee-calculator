<?php

namespace App\Factory;

trait Singleton
{
    private static $instance;

    public static function create()
    {
        $factory = new self();

        if (!self::$instance) {
            self::$instance = $factory->createInstance();
        }

        return self::$instance;
    }

    abstract protected function createInstance();
}

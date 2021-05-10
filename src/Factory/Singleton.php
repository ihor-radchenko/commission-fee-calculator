<?php

namespace App\Factory;

/**
 * Trait Singleton.
 *
 * @description factories create a singleton with this trait.
 */
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

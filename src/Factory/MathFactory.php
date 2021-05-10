<?php

namespace App\Factory;

use App\Contract\Service\Math;
use App\Service\BcMath;

class MathFactory
{
    /**
     * @description scale = 3, since current currencies have a maximum value of precision = 2.
     */
    public static function create(): Math
    {
        return new BcMath(3);
    }
}

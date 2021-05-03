<?php

namespace App\Factory;

use App\Service\BcMath;
use App\Contract\Service\Math;

class MathFactory
{
    public static function create(): Math
    {
        return new BcMath(3);
    }
}

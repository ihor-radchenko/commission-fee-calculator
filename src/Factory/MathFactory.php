<?php

namespace App\Factory;

use App\Contract\Service\Math;
use App\Service\BcMath;

class MathFactory
{
    public static function create(): Math
    {
        return new BcMath(3);
    }
}

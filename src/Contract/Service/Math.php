<?php

namespace App\Contract\Service;

use App\Entity\Money;

interface Math
{
    public function add(Money $money, Money $anotherMoney): Money;

    public function sub(Money $money, Money $anotherMoney): Money;

    public function mul(Money $money, Money $anotherMoney): Money;

    public function div(Money $money, Money $anotherMoney): Money;
}

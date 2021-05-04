<?php

namespace App\Entity;

use App\Factory\CommissionStrategyFactory;
use DateTimeImmutable;

class Operation
{
    private $date;

    private $type;

    private $user;

    private $money;

    private $prev;

    public function __construct(DateTimeImmutable $date, string $type, User $user, Money $money, ?Operation $prev = null)
    {
        $this->date = $date;
        $this->type = $type;
        $this->user = $user;
        $this->money = $money;
        $this->prev = $prev;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getPrev(): ?Operation
    {
        return $this->prev;
    }

    public function getCommission(): Money
    {
        return CommissionStrategyFactory::createFor($this)->execute($this->getMoney());
    }
}

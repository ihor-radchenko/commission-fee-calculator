<?php

namespace App\Entity;

use DateTimeImmutable;

class Operation
{
    private $date;

    private $type;

    private $user;

    private $money;

    public function __construct(DateTimeImmutable $date, string $type, User $user, Money $money)
    {
        $this->date = $date;
        $this->type = $type;
        $this->user = $user;
        $this->money = $money;
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
}
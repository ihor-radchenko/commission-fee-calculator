<?php

namespace App\Entity;

use Carbon\Carbon;

class Operation
{
    private $date;

    private $type;

    private $user;

    private $money;

    public function __construct(Carbon $date, string $type, User $user, Money $money)
    {
        $this->date = $date;
        $this->type = $type;
        $this->user = $user;
        $this->money = $money;
    }

    public function getDate(): Carbon
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
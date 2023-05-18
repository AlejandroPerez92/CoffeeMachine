<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Domain;

use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final class Product
{
    private string $name;
    private Money $price;
    private PositiveInteger $limit;

    public function __construct(string $name, Money $price, PositiveInteger $limit)
    {
        $this->name = $name;
        $this->price = $price;
        $this->limit = $limit;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function limit(): PositiveInteger
    {
        return $this->limit;
    }
}
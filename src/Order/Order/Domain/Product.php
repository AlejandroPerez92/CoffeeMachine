<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain;

use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final readonly class Product
{
    public function __construct(
        public string $name,
        public Money $price,
        public PositiveInteger $limit,
    ) {
    }
}
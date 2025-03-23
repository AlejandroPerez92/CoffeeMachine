<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain;

use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final readonly class Promotion
{
    public function __construct(
        public string $applyProduct,
        public PositiveInteger $fromUnits,
        public string $productToAdd,
    ) {
    }

    public function isApplicable(OrderLine $orderLine): bool
    {
        return $orderLine->units->moreThanOrEqual($this->fromUnits);
    }

}
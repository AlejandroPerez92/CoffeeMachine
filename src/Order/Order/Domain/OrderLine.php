<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\LimitUnitsException;
use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final readonly class OrderLine
{
    public function __construct(
        public OrderLineId $id,
        public string $productName,
        public Money $total,
        public PositiveInteger $units,
        public \DateTimeImmutable $created,
    ) {
    }

    public static function Create(Product $product, PositiveInteger $units): self
    {
        $total = new Money($product->price->value() * $units->value());
        $line = new self(OrderLineId::Create(), $product->name, $total, $units, new \DateTimeImmutable());
        $line->checkLimitUnits($product, $units);

        // TODO dispatch domain events
        return $line;
    }

    private function checkLimitUnits(Product $product, PositiveInteger $units)
    {
        if ($units->lessThanOrEqual($product->limit)) {
            return;
        }
        throw new LimitUnitsException();
    }
}
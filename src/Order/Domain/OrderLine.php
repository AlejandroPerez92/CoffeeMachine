<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Domain;

use AlexPerez\CoffeeMachine\Order\Domain\Exception\LimitUnitsException;
use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final class OrderLine
{
    private OrderLineId $id;
    private string $productName;
    private Money $total;
    private PositiveInteger $units;
    private \DateTimeImmutable $created;

    public function __construct(
        OrderLineId $id,
        string $productName,
        Money $total,
        PositiveInteger $units,
        \DateTimeImmutable $created)
    {
        $this->id = $id;
        $this->productName = $productName;
        $this->total = $total;
        $this->units = $units;
        $this->created = $created;
    }

    public static function Create(Product $product, PositiveInteger $units): self
    {
        $total = new Money($product->price()->value() * $units->value());
        $line = new self(OrderLineId::Create(), $product->name(), $total, $units, new \DateTimeImmutable());
        $line->checkLimitUnits($product, $units);

        // TODO dispatch domain events
        return $line;
    }

    public function productName(): string
    {
        return $this->productName;
    }

    public function created(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function id(): OrderLineId
    {
        return $this->id;
    }

    public function total(): Money
    {
        return $this->total;
    }

    public function units(): PositiveInteger
    {
        return $this->units;
    }

    private function checkLimitUnits(Product $product, PositiveInteger $units)
    {
        if($units->lessThanOrEqual($product->limit())){
            return;
        }
        throw new LimitUnitsException();
    }
}
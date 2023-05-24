<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Domain;

use AlexPerez\CoffeeMachine\Shared\Domain\Aggregate\AggregateRoot;

final class ProductSales extends AggregateRoot
{
    public function __construct(
        private string $name,
        private int $total,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function incrementTotal(int $total): void
    {
        $this->total += $total;
    }

    public static function aggregateId(): string
    {
        return 'product-sale';
    }
}
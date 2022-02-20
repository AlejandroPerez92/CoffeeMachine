<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Domain;

final class ProductSale
{
    private string $name;
    private int $total;

    public function __construct(string $name, int $total)
    {
        $this->name = $name;
        $this->total = $total;
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
}
<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Application\Command;

use Deliverea\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final class CreateOrderLineCommand
{
    private string $productName;
    private int $units;
    private string $orderId;

    public function __construct(string $productName, int $units, string $orderId)
    {
        $this->productName = $productName;
        $this->units = $units;
        $this->orderId = $orderId;
    }

    public function productName(): string
    {
        return $this->productName;
    }

    public function units(): int
    {
        return $this->units;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

}
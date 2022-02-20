<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Application\Command;

final class UpdateSaleCommand
{
    private string $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

}
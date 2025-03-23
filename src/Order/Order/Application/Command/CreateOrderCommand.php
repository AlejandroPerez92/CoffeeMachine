<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Application\Command;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final readonly class CreateOrderCommand
{
    public function __construct(public OrderId $orderId, public bool $hot)
    {
    }
}
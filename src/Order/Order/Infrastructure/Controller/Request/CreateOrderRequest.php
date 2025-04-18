<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Request;

final readonly class CreateOrderRequest
{
    public function __construct(
        public bool $extraHot = false
    ) {
    }
}
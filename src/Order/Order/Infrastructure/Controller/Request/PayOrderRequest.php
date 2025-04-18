<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Request;

final readonly class PayOrderRequest
{
    public function __construct(
        public int $amount = 0
    ) {
    }
}
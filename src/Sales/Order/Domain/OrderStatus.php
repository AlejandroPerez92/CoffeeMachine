<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Domain;

enum OrderStatus: string
{
    case paid = 'paid';
    case pending = 'pending';
}
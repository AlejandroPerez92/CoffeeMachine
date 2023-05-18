<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Domain\Exception;

final class NotFoundOrderException extends \LogicException
{

    public function __construct(string $orderId)
    {
        parent::__construct(sprintf("Order %s not found",$orderId));
    }
}
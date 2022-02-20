<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Domain\Exception;

use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;

final class NotFoundOrderException extends \LogicException
{

    public function __construct(string $orderId)
    {
        parent::__construct(sprintf("Order %s not found",$orderId));
    }
}
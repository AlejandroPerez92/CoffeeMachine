<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Domain\Exception;

use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;

final class NotFoundOrderException extends \LogicException
{

    public function __construct(OrderId $orderId)
    {
        parent::__construct(sprintf("Order %s not found",$orderId->value()));
    }
}
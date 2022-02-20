<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Domain\Exception;

use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;

final class NotFoundSalesException extends \LogicException
{
    public function __construct(string $product)
    {
        parent::__construct(sprintf("Product sales %s not found",$product));
    }
}
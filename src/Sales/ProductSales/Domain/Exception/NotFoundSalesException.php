<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception;

final class NotFoundSalesException extends \LogicException
{
    public function __construct(string $product)
    {
        parent::__construct(sprintf("Product sales %s not found",$product));
    }
}